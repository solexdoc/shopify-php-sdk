<?php

namespace Shopify\Enum\Fields;

class LineItemByFulfillmentOrderFields extends AbstractObjectEnum
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
