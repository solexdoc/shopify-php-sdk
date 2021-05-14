<?php

namespace Shopify\Enum\Fields;

class LineItemByFulfillmentOrderFulfillmentOrderLineItemFields extends AbstractObjectEnum
{
    const ID= 'id';
    const QUANTITY = 'quantity';

    public function getFieldTypes()
    {
        return array(
            'id' => 'integer',
            'quantity' => 'integer'
        );
    }
}
