<?php

namespace Shopify\Service;

use Shopify\Object\Fulfillment;

class FulfillmentService extends AbstractService
{
    public function all($orderId, array $params = [])
    {
        $endpoint = 'orders/'.$orderId.'/fulfillments.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createCollection(Fulfillment::class, $response['fulfillments']);
    }

    public function allForFulfillmentOrder($fulfillmentOrderId, array $params = [])
    {
        $endpoint = 'fulfillment_orders/'.$fulfillmentOrderId.'/fulfillments.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createCollection(Fulfillment::class, $response['fulfillments']);
    }

    public function count($orderId, array $params = [])
    {
        $endpoint = 'orders/'.$orderId.'/fulfillments/count.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $response['count'];
    }

    public function get($orderId, $fulfillmentId, array $params = [])
    {
        $endpoint = 'orders/'.$orderId.'/fulfillments/'.$fulfillmentId.'.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createObject(Fulfillment::class, $response['fulfillments']);
    }

    public function createForFulfillmentOrder(Fulfillment &$fulfillment)
    {
        $data = $fulfillment->exportData();
        $endpoint = 'fulfillments.json';
        $response = $this->request(
            $endpoint, 'POST', array(
                'fulfillment' => $data
            )
        );
        $fulfillment->setData($response['fulfillment']);
    }

    public function create($orderId, Fulfillment &$fulfillment)
    {
        $data = $fulfillment->exportData();
        $endpoint = 'orders/'.$orderId.'/fulfillments.json';
        $response = $this->request(
            $endpoint, 'POST', array(
            'fulfillment' => $data
            )
        );
        $fulfillment->setData($response['fulfillment']);
    }

    public function update($orderId, Fulfillment &$fulfillment)
    {
        $data = $fulfillment->exportData();
        $endpoint = 'orders/'.$orderId.'/fulfillments/'.$fulfillment->id.'.json';
        $response = $this->request(
            $endpoint, 'POST', array(
            'fulfillment' => $data
            )
        );
        $fulfillment->setData($response['fulfillment']);
    }

    public function complete($orderId, Fulfillment &$fulfillment)
    {
        $endpoint = 'orders/'.$orderId.'/fulfillments/'.$fulfillment->id.'/complete.json';
        $response = $this->request($endpoint, 'POST');
        $fulfillment->setData($response['fulfillment']);
    }

    public function cancel($orderId, Fulfillment &$fulfillment)
    {
        $endpoint = 'orders/'.$orderId.'/fulfillments/'.$fulfillment->id.'/cancel.json';
        $response = $this->request($endpoint, 'POST');
        $fulfillment->setData($response['fulfillment']);
    }

    public function open($orderId, Fulfillment &$fulfillment)
    {
        $endpoint = 'orders/'.$orderId.'/fulfillments/'.$fulfillment->id.'/open.json';
        $response = $this->request($endpoint, 'POST');
        $fulfillment->setData($response->fulfillment);
    }
}
