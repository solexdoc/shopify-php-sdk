<?php

namespace Shopify\Enum\Fields;

class InventoryItemFields extends AbstractObjectEnum
{
    const ID = 'id';
    const SKU = 'sku';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const REQUIRES_SHIPPING = 'requires_shipping';
    const COST = 'cost';
    const COUNTRY_CODE_OF_ORIGIN = 'country_code_of_origin';
    const PROVINCE_CODE_OF_ORIGIN = 'province_code_of_origin';
    const HARMONIZED_SYSTEM_CODE = 'harmonized_system_code';
    const TRACKED = 'tracked';
    const COUNTRY_HARMONIZED_SYSTEM_CODES = 'country_harmonized_system_codes';
    const ADMIN_GRAPHQL_API_ID = 'admin_graphql_api_id';

    public function getFieldTypes()
    {
        return array(
            'id' => 'string',
            'sku' => 'string',
            'created_at' => 'DateTime',
            'updated_at' => 'DateTime',
            'requires_shipping' => 'boolean',
            'cost' => 'string',
            'country_code_of_origin' => 'string',
            'province_code_of_origin' => 'string',
            'harmonized_system_code' => 'string',
            'tracked' => 'boolean',
            'country_harmonized_system_codes' => 'CountryHarmonizedSystemCode[]',
            'admin_graphql_api_id' => 'string'
        );
    }
}
