<?php

namespace Shopify\Service;

use Shopify\Object\Fulfillment;
use Shopify\Object\FulfillmentOrder;

class FulfillmentOrderService extends AbstractService
{
    public function all($orderId, array $params = [])
    {
        $endpoint = 'orders/'.$orderId.'/fulfillment_orders.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createCollection(FulfillmentOrder::class, $response['fulfillment_orders']);
    }

    public function get($fulfillmentOrderId, array $params = [])
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrderId.'.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createObject(FulfillmentOrder::class, $response['fulfillment_order']);
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
