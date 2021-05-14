<?php

namespace Shopify\Enum\Fields;

class LineItemByFulfillmentOrderFields extends AbstractObjectEnum
{
    const FULLFILLMENT_ORDER_ID = 'fulfillment_order_id';
    const FULFILLMENT_ORDER_LINE_ITEMS = 'fulfillment_order_line_items';

    public function getFieldTypes()
    {
        return array(
            'fulfillment_order_id' => 'integer',
            'fulfillment_order_line_items' => 'LineItemByFulfillmentOrderFulfillmentOrderLineItem[]'
        );
    }
}
