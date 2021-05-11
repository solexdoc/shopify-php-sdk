<?php

namespace Shopify\Enum\Fields;

class CountryHarmonizedSystemCodeFields extends AbstractObjectEnum
{
    const HARMONIZED_SYSTEM_CODE = 'harmonized_system_code';
    const COUNTRY_CODE = 'country_code';

    public function getFieldTypes()
    {
        return array(
            'harmonized_system_code' => 'string',
            'country_code' => 'string'
        );
    }
}
