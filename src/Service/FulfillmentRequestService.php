<?php

namespace Shopify\Service;

use Shopify\Object\InventoryItem;
use Shopify\Object\Order;

class FulfillmentRequestService extends AbstractService
{
    /**
     * Accept fulfillmentRequest
     *
     * @link   https://help.shopify.com/api/reference/order#index
     */
    public function acceptFulfillmentRequest(string $fulfillmentOrderId,$message="Fulfillment request accepted", array $params = [])
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrderId.'/fulfillment_request/accept.json';
        $response = $this->request($endpoint, 'POST', $params);

        $fulfillmentRequest = new \stdClass();
        $fulfillmentRequest->message = $message;
        $response = $this->request(
            $endpoint, 'POST', array(
                'fulfillment_request' => $fulfillmentRequest
            )
        );
    }
}
