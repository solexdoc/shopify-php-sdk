<?php

namespace Shopify\Enum\Fields;

class AssignedFulfillmentOrderDestinationFields extends AbstractObjectEnum
{
    const ID = 'id';
    const ADDRESS1 = 'address1';
    const ADDRESS2 = 'address2';
    const CITY = 'city';
    const COMPANY = 'company';
    const COUNTRY = 'country';
    const EMAIL = 'email';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const PHONE = 'phone';
    const PROVINCE = 'province';
    const ZIP = 'zip';

    public function getFieldTypes()
    {
        return array(
            'id' => 'string',
            'address1' => 'string',
            'address2' => 'string',
            'city' => 'string',
            'company' => 'string',
            'country' => 'string',
            'email' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'phone' => 'string',
            'province' => 'string',
            'zip' => 'string'
        );
    }
}
