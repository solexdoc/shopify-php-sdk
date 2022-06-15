<?php

namespace Shopify\Enum\Fields;

class InventoryLevelFields extends AbstractObjectEnum
{
    const AVAILABLE = 'available';
    const INVENTORY_ITEM_ID = 'inventory_item_id';
    const LOCATION_ID = 'location_id';
    const UPDATED_AT = 'updated_at';

    public function getFieldTypes()
    {
        return array(
            'available' => 'integer',
            'inventory_item_id' => 'string',
            'location_id' => 'string',
            'updated_at' => 'DateTime'
        );
    }
}
