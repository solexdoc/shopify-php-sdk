<?php

namespace Shopify\Enum\Fields;

class FulfillmentOrderMerchantRequestFields extends AbstractObjectEnum
{
    const MESSAGE = 'message';
    const REQUEST_OPTIONS = 'request_options';
    const KIND = 'kind';

    public function getFieldTypes()
    {
        return array(
            'message' => 'string',
            'request_options' => 'FulfillmentOrderMerchantRequestOption',
            'kind' => 'string',
        );
    }
}
