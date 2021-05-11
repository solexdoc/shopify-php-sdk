<?php

namespace Shopify\Service;

use Shopify\Object\InventoryItem;
use Shopify\Object\Order;

class InventoryItemService extends AbstractService
{
    /**
     * Retrieve a list of inventoryItems
     *
     * @link   https://help.shopify.com/api/reference/order#index
     * @param  array $params
     * @return Order[]
     */
    public function getByMultipleIds(array $params = [])
    {
        $endpoint = 'inventory_items.json';
        $response = $this->request($endpoint, 'GET', $params);
        return $this->createCollection(InventoryItem::class, $response['inventory_items']);
    }
}
