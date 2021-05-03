<?php

namespace Shopify\Service;

use Shopify\Object\AssignedFulfillmentOrder;
use Shopify\Object\Fulfillment;

class AssignedFulfillmentOrderService extends AbstractService
{
    public function all(array $params=[])
    {
        $endpoint = 'assigned_fulfillment_orders.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createCollection(AssignedFulfillmentOrder::class, $response['fulfillment_orders']);
    }
}
