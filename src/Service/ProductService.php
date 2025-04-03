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
        $endpoint = 'products.json';
        $allProducts = [];

        do {
            echo "Requesting endpoint: {$endpoint}\n";

            // The request() call returns the decoded body,
            // but the actual Guzzle response is stored in $this->lastResponse.
            $responseBody = $this->request($endpoint, 'GET', $params);

            // Retrieve headers from the raw response.
            $headers = $this->getLastResponse()->getHeaders();

            if (isset($responseBody['products']) && is_array($responseBody['products'])) {
                $numProducts = count($responseBody['products']);
                echo "Retrieved {$numProducts} products from this page.\n";
                $allProducts = array_merge($allProducts, $responseBody['products']);
            } else {
                echo "No products found in this response.\n";
            }

            // Check for pagination in the headers (using the lower-case 'link' header).
            $nextEndpoint = null;
            if (isset($headers['link'])) {
                // In some cases the header might be returned as an array.
                $linkHeader = is_array($headers['link']) ? implode(', ', $headers['link']) : $headers['link'];
                echo "Link header: {$linkHeader}\n";
                $links = $this->parseLinkHeader($linkHeader);
                if (isset($links['next'])) {
                    $nextEndpoint = $links['next'];
                    echo "Found next page: {$nextEndpoint}\n";
                } else {
                    echo "No next page link found in the header.\n";
                }
            } else {
                echo "No link header present in the response.\n";
            }

            // If there is a next page, set it as the endpoint,
            // and clear params since the pagination URL already includes them.
            $endpoint = $nextEndpoint;
            $params = [];
        } while ($endpoint);

        echo "Total products retrieved: " . count($allProducts) . "\n";

        return $this->createCollection(Product::class, $allProducts);
    }

    /**
     * Parse the link header from Shopify to extract pagination URLs.
     *
     * Example header:
     * <https://example.myshopify.com/admin/api/2025-04/products.json?limit=250&page_info=...>; rel="next",
     * <https://example.myshopify.com/admin/api/2025-04/products.json?limit=250&page_info=...>; rel="previous"
     *
     * @param string $header
     * @return array
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
            // Trim the URL (remove angle brackets and whitespace)
            $url = trim($section[0], " <>\t\n\r\0\x0B");
            $rel = null;
            // Process each segment to find the rel value and remove quotes if present.
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
