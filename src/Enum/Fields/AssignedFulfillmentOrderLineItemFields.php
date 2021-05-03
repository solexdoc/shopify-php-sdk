<?php

namespace Shopify\Enum\Fields;

class AssignedFulfillmentOrderLineItemFields extends AbstractObjectEnum
{
    const ID = 'id';
    const SHOP_ID = 'shop_id';
    const FULFILLMENT_ORDER_ID = 'fulfillment_order_id';
    const QUANTITY = 'quantity';
    const LINE_ITEM_ID = 'line_item_id';
    const INVENTORY_ITEM_ID = 'inventory_item_id';
    const FULFILLABLE_QUANTITY = 'fulfillable_quantity';
    const VARIANT_ID = 'variant_id';

    public function getFieldTypes()
    {
        return array(
            'id' => 'string',
            'shop_id' => 'string',
            'fulfillment_order_id' => 'string',
            'quantity' => 'integer',
            'line_item_id' => 'string',
            'inventory_item_id' => 'string',
            'fulfillable_quantity' => 'integer',
            'variant_id' => 'string'
        );
    }
}
