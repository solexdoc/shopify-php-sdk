<?php

namespace Shopify\Service;

use Shopify\Object\CarrierService;
use Shopify\Object\FulfillmentService;

class CarrierServiceService extends AbstractService
{
    public function all(array $params = [])
    {
        $endpoint = 'carrier_services.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createCollection(CarrierService::class, $response['carrier_services']);
    }

    public function get($carrierServiceId, array $params = [])
    {
        $endpoint = 'carrier_services/'.$carrierServiceId.'.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createObject(CarrierService::class, $response['carrier_service']);
    }

    public function create(CarrierService &$carrierService)
    {
        $data = $carrierService->exportData();
        $endpoint = 'carrier_services.json';
        $response = $this->request(
            $endpoint, 'POST', array(
            'carrier_service' => $data
            )
        );
        $carrierService->setData($response['carrier_service']);
    }

    public function update(CarrierService &$carrierService)
    {
        $data = $carrierService->exportData();
        $endpoint = 'carrier_service/'.$carrierService->id.'.json';
        $response = $this->request(
            $endpoint, 'PUT', array(
            'carrier_service' => $data
            )
        );
        $carrierService->setData($response['carrier_service']);
    }
    public function delete(CarrierService &$carrierService)
    {
        $endpoint = 'carrier_services/'.$carrierService->id.'.json';
        $response = $this->request($endpoint, 'DELETE');
        $carrierService->setData($response['fulfillment_service']);
    }
}
