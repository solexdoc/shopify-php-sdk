<?php

namespace Shopify\Enum\Fields;

class FulfillmentTrackingInfoFields extends AbstractObjectEnum
{
    const NUMBER = 'number';
    const URL = 'url';
    const COMPANY = 'company';

    public function getFieldTypes()
    {
        return array(
            'number' => 'string',
            'url' => 'string',
            'company' => 'string'
        );
    }
}
