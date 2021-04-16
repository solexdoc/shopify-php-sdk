<?php

namespace Shopify\Enum\Fields;

class FulfillmentServiceFields extends AbstractObjectEnum
{
    const ID = 'id';
    const EMAIL = 'email';
    const SERVICE_NAME = 'service_name';
    const INCLUDE_PENDING_STOCK = 'include_pending_stock';
    const CALLBACK_URL = 'callback_url';
    const FORMAT = 'format';
    const FULFILLMENT_ORDERS_OPT_IN = 'fulfillment_orders_opt_in';
    const HANDLE = 'handle';
    const INVENTORY_MANAGEMENT = 'inventory_management';
    const LOCATION_ID = 'location_id';
    const NAME = 'name';
    const PROVIDER_ID = 'provider_id';
    const REQUIRES_SHIPPING_METHOD = 'requires_shipping_method';
    const TRACKING_SUPPORT = 'tracking_support';

    public function getFieldTypes()
    {
        return array(
            'id' => 'integer',
            'email' => 'string',
            'service_name' => 'string',
            'handle' => 'string',
            'fulfillment_orders_opt_in' => 'boolean',
            'include_pending_stock' => 'boolean',
            'provider_id' => 'integer',
            'location_id' => 'integer',
            'callback_url' => 'string',
            'tracking_support' => 'boolean',
            'inventory_management' => 'boolean'
        );
    }
}
