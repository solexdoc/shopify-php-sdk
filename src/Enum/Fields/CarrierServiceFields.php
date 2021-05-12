<?php

namespace Shopify\Enum\Fields;

class CarrierServiceFields extends AbstractObjectEnum
{
    //used for input
    const NAME = 'name';
    const CALLBACK_URL = 'callback_url';
    const SERVICE_DISCOVERY = 'service_discovery';

    //returned values added
    const ID = 'id';
    const ACTIVE = 'active';
    const CARRIER_SERVICE_TYPE = 'carrier_service_type';
    const ADMIN_GRAPHQL_API_ID = 'admin_graphql_api_id';
    const FORMAT = 'format';

    public function getFieldTypes()
    {
        return array(
            'id' => 'integer',
            'name' => 'string',
            'active' => 'boolean',
            'service_discovery' => 'boolean',
            'carrier_service_type' => 'string',
            'admin_graphql_api_id' => 'string',
            'format' => 'string',
            'callback_url' => 'string'
        );
    }
}
