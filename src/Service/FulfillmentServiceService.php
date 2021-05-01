<?php

namespace Shopify\Service;

use Shopify\Object\FulfillmentService;

class FulfillmentServiceService extends AbstractService
{
    public function all(array $params = [])
    {
        //By default, I prefer getting only the fulfillment_services created by us.
        $endpoint = 'fulfillment_services.json?scope=current_client';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createCollection(FulfillmentService::class, $response['fulfillment_services']);
    }

    public function get($fulfillmentServiceId, array $params = [])
    {
        $endpoint = 'fulfillment_services/'.$fulfillmentServiceId.'.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createObject(FulfillmentService::class, $response['fulfillment_service']);
    }

    public function create(FulfillmentService &$fulfillmentService)
    {
        $data = $fulfillmentService->exportData();
        $endpoint = 'fulfillment_services.json';
        $response = $this->request(
            $endpoint, 'POST', array(
            'fulfillment_service' => $data
            )
        );

        $fulfillmentService->setData($response['fulfillment_service']);
    }

    public function update($orderId, FulfillmentService &$fulfillmentService)
    {
        $data = $fulfillmentService->exportData();
        $endpoint = 'fulfillment_services/'.$fulfillmentService->id.'.json';
        $response = $this->request(
            $endpoint, 'PUT', array(
            'fulfillment_service' => $data
            )
        );
        $fulfillmentService->setData($response['fulfillment_service']);
    }
    public function delete(FulfillmentService &$fulfillmentService)
    {
        $endpoint = 'fulfillment_services/'.$fulfillmentService->id.'.json';
        $response = $this->request($endpoint, 'DELETE');
        $fulfillmentService->setData($response['fulfillment_service']);
    }
}
