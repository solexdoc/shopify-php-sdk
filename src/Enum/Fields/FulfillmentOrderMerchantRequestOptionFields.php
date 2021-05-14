<?php

namespace Shopify\Enum\Fields;

class FulfillmentOrderMerchantRequestOptionFields extends AbstractObjectEnum
{
    const SHIPPING_METHOD = 'shipping_method';
    const NOTE = 'note';
    const date = 'date';

    public function getFieldTypes()
    {
        return array(
            'shipping_method' => 'string',
            'note' => 'string',
            'date' => 'DateTime'
        );
    }
}
