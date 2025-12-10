<?php

namespace Shopify\Service;

use Shopify\Object\Fulfillment;
use Shopify\Object\FulfillmentOrder;

class FulfillmentOrderService extends AbstractService
{
    /**
     * Parse the Link header from Shopify to extract pagination URLs.
     *
     * Expected format (comma-separated):
     * <https://example.com/path?query=...>; rel="next", <https://example.com/path?query=...>; rel="previous"
     *
     * @param string $header
     * @return array  Associative array keyed by the rel value (e.g. "next", "previous")
     */
    private function parseLinkHeader($header): array
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

    public function all($orderId, array $params = [])
    {
        // Initial endpoint for this order's fulfillment orders
        $endpoint = 'orders/' . $orderId . '/fulfillment_orders.json';
        $allFulfillmentOrders = [];

        // Map of fulfillment_order_id => index in $allFulfillmentOrders
        // so we can merge line_items across pages when the same FO shows up again
        $foIndex = [];

        // Retrieve the base URI from the client configuration via a getter.
        $client = $this->getClient();
        $config = $client->getConfig();
        $baseUri = isset($config['base_uri']) ? $config['base_uri'] : '';
        if ($baseUri instanceof \Psr\Http\Message\UriInterface) {
            $baseUri = (string) $baseUri;
        }
        $basePath = $baseUri ? ltrim(parse_url($baseUri, PHP_URL_PATH), '/') : '';

        do {
            echo "Requesting endpoint: {$endpoint}\n";

            // Perform the API call. The decoded JSON is returned,
            // while the full response (with headers) is stored in $this->lastResponse.
            $responseBody = $this->request($endpoint, 'GET', $params);
            $rawResponse  = $this->getLastResponse();
            $headers      = $rawResponse->getHeaders();

            if (isset($responseBody['fulfillment_orders']) && is_array($responseBody['fulfillment_orders'])) {
                $numFOs = count($responseBody['fulfillment_orders']);
                echo "Retrieved {$numFOs} fulfillment orders from this page.\n";

                foreach ($responseBody['fulfillment_orders'] as $fo) {
                    $id = isset($fo['id']) ? $fo['id'] : null;

                    // If we've already seen this fulfillment order ID on a previous page,
                    // merge the line_items instead of adding a duplicate FO.
                    if ($id !== null && isset($foIndex[$id])) {
                        $idx = $foIndex[$id];

                        // Merge line_items across pages
                        if (isset($fo['line_items']) && is_array($fo['line_items'])) {
                            $existingItems = isset($allFulfillmentOrders[$idx]['line_items'])
                            && is_array($allFulfillmentOrders[$idx]['line_items'])
                                ? $allFulfillmentOrders[$idx]['line_items']
                                : [];

                            // Index existing items by id to avoid duplicates
                            $byId = [];
                            foreach ($existingItems as $item) {
                                if (isset($item['id'])) {
                                    $byId[$item['id']] = $item;
                                } else {
                                    // Item without id, just append with a numeric key
                                    $byId[] = $item;
                                }
                            }

                            // Add or overwrite items from this page
                            foreach ($fo['line_items'] as $item) {
                                if (isset($item['id'])) {
                                    $byId[$item['id']] = $item;
                                } else {
                                    $byId[] = $item;
                                }
                            }

                            $allFulfillmentOrders[$idx]['line_items'] = array_values($byId);
                        }

                        // If needed, you could also reconcile other fields here,
                        // but generally they should be identical across pages.
                    } else {
                        // New fulfillment order, store it and remember its index
                        $allFulfillmentOrders[] = $fo;
                        if ($id !== null) {
                            $foIndex[$id] = count($allFulfillmentOrders) - 1;
                        }
                    }
                }
            } else {
                echo "No fulfillment orders found in this response.\n";
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
                $parsedUrl  = parse_url($nextEndpoint);
                $newEndpoint = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : '';
                $newParams  = [];
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $newParams);
                }

                // Remove the base path (if present) from the new endpoint.
                if ($basePath && strpos($newEndpoint, $basePath) === 0) {
                    $newEndpoint = ltrim(substr($newEndpoint, strlen($basePath)), '/');
                }

                echo "Parsed next endpoint: {$newEndpoint} with params: " . json_encode($newParams) . "\n";
                $endpoint = $newEndpoint;
                $params   = $newParams;
            } else {
                $endpoint = null;
            }

            // Respect rate limits by waiting 1 second between calls.
            if ($endpoint) {
                echo "Waiting 1 second before next API call...\n";
                sleep(1);
            }
        } while ($endpoint);

        echo "Total fulfillment orders collected (after merging): " . count($allFulfillmentOrders) . "\n";

        return $this->createCollection(FulfillmentOrder::class, $allFulfillmentOrders);
    }


    public function get($fulfillmentOrderId, array $params = [])
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrderId.'.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createObject(FulfillmentOrder::class, $response['fulfillment_order']);
    }

    public function cancelWithId(string $fulfillmentOrderId)
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrderId.'/cancel.json';
        $response = $this->request($endpoint, 'POST');
    }

    public function cancel(FulfillmentOrder &$fulfillmentOrder)
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrder->id.'/cancel.json';
        $response = $this->request($endpoint, 'POST');
        $fulfillmentOrder->setData($response['fulfillment_order']);
    }

    public function open(FulfillmentOrder &$fulfillmentOrder)
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrder->id.'/open.json';
        $response = $this->request($endpoint, 'POST');
        $fulfillmentOrder->setData($response['fulfillment_order']);
    }

    public function reschedule(FulfillmentOrder &$fulfillmentOrder, \DateTime $newFulfillAt)
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrder->id.'/reschedule.json';

        $reschedule = new \stdClass();
        $reschedule->new_fulfill_at = $newFulfillAt->format("Y-m-d");

        $response = $this->request($endpoint, 'POST',['fulfillment_order' =>  $reschedule]);
        $fulfillmentOrder->setData($response['fulfillment_order']);
    }

    public function closeAsIncomplete(FulfillmentOrder &$fulfillmentOrder, string $message)
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrder->id.'/reschedule.json';

        $closing = new \stdClass();
        $closing->message = $message;

        $response = $this->request($endpoint, 'POST',['fulfillment_order' =>  $closing]);
        $fulfillmentOrder->setData($response['fulfillment_order']);
    }

}
