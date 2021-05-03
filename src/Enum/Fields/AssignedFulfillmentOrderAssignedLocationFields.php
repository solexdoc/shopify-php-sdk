<?php

namespace Shopify\Enum\Fields;

class AssignedFulfillmentOrderAssignedLocationFields extends AbstractObjectEnum
{

    const ADDRESS1 = 'address1';
    const ADDRESS2 = 'address2';
    const CITY = 'city';
    const COUNTRY_CODE = 'country_code';
    const LOCATION_ID = 'location_id';
    const NAME = 'name';
    const PHONE = 'phone';
    const PROVINCE = 'province';
    const ZIP = 'zip';

    public function getFieldTypes()
    {
        return array(
            'address1' => 'string',
            'address2' => 'string',
            'city' => 'string',
            'country_code' => 'string',
            'location_id' => 'string',
            'name' => 'string',
            'phone' => 'string',
            'province' => 'string',
            'zip' => 'string'
        );
    }
}
