<?php

namespace Shopify\Service;

use Shopify\Object\InventoryLevel;
use Shopify\Object\Order;

class InventoryLevelService extends AbstractService
{
    public function setData(InventoryLevel &$inventoryLevel)
    {
        $data = $inventoryLevel->exportData();
        $endpoint = 'inventory_levels/set.json';
        $response = $this->request(
            $endpoint, 'POST', $data
        );
        $inventoryLevel->setData($response['inventory_level']);
    }
}
