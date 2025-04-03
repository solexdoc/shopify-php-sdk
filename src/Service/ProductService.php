<?php

namespace Shopify\Service;

use Shopify\Object\Product;

class ProductService extends AbstractService
{
    /**
     * Receive a lists of all Products
     *
     * @link   https://help.shopify.com/api/reference/product#index
     * @param  array $params
     * @return Product[]
     */
    public function all(array $params = [])
    {
        // Start with the initial endpoint (relative path)
        $endpoint = 'products.json';
        $allProducts = [];

        // Retrieve the base URI from the client configuration via a getter.
        $client = $this->getClient();
        $config = $client->getConfig();
        $baseUri = isset($config['base_uri']) ? $config['base_uri'] : '';
        if ($baseUri instanceof \Psr\Http\Message\UriInterface) {
            $baseUri = (string)$baseUri;
        }
        $basePath = $baseUri ? ltrim(parse_url($baseUri, PHP_URL_PATH), '/') : '';

        do {
            echo "Requesting endpoint: {$endpoint}\n";

            // Perform the API call. The decoded JSON is returned,
            // while the full response (with headers) is stored in $this->lastResponse.
            $responseBody = $this->request($endpoint, 'GET', $params);
            $rawResponse = $this->getLastResponse();
            $headers = $rawResponse->getHeaders();

            if (isset($responseBody['products']) && is_array($responseBody['products'])) {
                $numProducts = count($responseBody['products']);
                echo "Retrieved {$numProducts} products from this page.\n";
                $allProducts = array_merge($allProducts, $responseBody['products']);
            } else {
                echo "No products found in this response.\n";
            }

            // Look for a "link" header (lowercase) to check for pagination.
            $nextEndpoint = null;
            if (isset($headers['link'])) {
                $linkHeader = is_array($headers['link']) ? implode(', ', $headers['link']) : $headers['link'];
                echo "Link header: {$linkHeader}\n";
                $links = $this->parseLinkHeader($linkHeader);
                if (isset($links['next'])) {
                    $nextEndpoint = $links['next'];
                    echo "Found next page link: {$nextEndpoint}\n";
                } else {
                    echo "No next page link found in the header.\n";
                }
            } else {
                echo "No link header present in the response.\n";
            }

            // If a next link exists, extract the relative endpoint and query parameters.
            if ($nextEndpoint) {
                $parsedUrl = parse_url($nextEndpoint);
                $newEndpoint = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : '';
                $newParams = [];
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $newParams);
                }
                // Remove the base path (if present) from the new endpoint.
                if ($basePath && strpos($newEndpoint, $basePath) === 0) {
                    $newEndpoint = ltrim(substr($newEndpoint, strlen($basePath)), '/');
                }
                echo "Parsed next endpoint: {$newEndpoint} with params: " . json_encode($newParams) . "\n";
                $endpoint = $newEndpoint;
                $params = $newParams;
            } else {
                $endpoint = null;
            }

            // Respect rate limits by waiting 1 second between calls.
            if ($endpoint) {
                echo "Waiting 1 second before next API call...\n";
                sleep(1);
            }
        } while ($endpoint);

        echo "Total products retrieved: " . count($allProducts) . "\n";
        return $this->createCollection(Product::class, $allProducts);
    }

    /**
     * Parse the Link header from Shopify to extract pagination URLs.
     *
     * Expected format (comma-separated):
     * <https://example.com/path?query=...>; rel="next", <https://example.com/path?query=...>; rel="previous"
     *
     * @param string $header
     * @return array  Associative array keyed by the rel value (e.g. "next", "previous")
     */
    private function parseLinkHeader($header)
    {
        $links = [];
        $parts = explode(',', $header);
        foreach ($parts as $part) {
            $section = explode(';', $part);
            if (count($section) < 2) {
                continue;
            }
            $url = trim($section[0], " <>\t\n\r\0\x0B");
            $rel = null;
            foreach ($section as $seg) {
                if (strpos($seg, 'rel=') !== false) {
                    $rel = trim(str_replace('rel=', '', $seg), " \"");
                }
            }
            if ($rel) {
                $links[$rel] = $url;
            }
        }
        return $links;
    }
    /**
     * Receive a count of all Products
     *
     * @link   https://help.shopify.com/api/reference/product#count
     * @param  array $params
     * @return integer
     */
    public function count(array $params = [])
    {
        $endpoint = 'products/count.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $response['count'];
    }

    /**
     * Receive a single product
     *
     * @link   https://help.shopify.com/api/reference/product#show
     * @param  integer $productId
     * @param  array   $fields
     * @return Product
     */
    public function get($productId, array $fields = [])
    {
        $params = array();
        if (!empty($fields)) {
            $params['fields'] = $fields;
        }
        $endpoint = 'products/'.$productId.'.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createObject(Product::class, $response['product']);
    }

    /**
     * Create a new Product
     *
     * @link   https://help.shopify.com/api/reference/product#create
     * @param  Product $product
     * @return void
     */
    public function create(Product &$product)
    {
        $data = $product->exportData();
        $endpoint = 'products.json';
        $response = $this->request(
            $endpoint, 'POST', array(
            'product' => $data
            )
        );
        $product->setData($response['product']);
    }

    /**
     * Modify an existing product
     *
     * @link   https://help.shopify.com/api/reference/product#update
     * @param  Product $product
     * @return void
     */
    public function update(Product &$product)
    {
        $data = $product->exportData();
        $endpoint = 'products/'.$product->id.'.json';
        $response = $this->request(
            $endpoint, 'PUT', array(
            'product' => $data
            )
        );
        $product->setData($response['product']);
    }

    /**
     * Remove a product
     *
     * @link   https://help.shopify.com/api/reference/product#destroy
     * @param  Product $product
     * @return void
     */
    public function delete(Product &$product)
    {
        $endpoint = 'products/'.$product->id.'.json';
        $this->request($endpoint, 'DELETE');
        return;
    }
}
