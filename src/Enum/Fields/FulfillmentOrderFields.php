<?php

namespace Shopify\Enum\Fields;

class FulfillmentOrderFields extends AbstractObjectEnum
{
    const CREATED_AT = 'created_at';
    const ID = 'id';
    const LINE_ITEMS = 'line_items';
    const NOTIFY_CUSTOMER = 'notify_customer';
    const ORDER_ID = 'order_id';
    const RECEIPT = 'receipt';
    const STATUS = 'status';
    const TRACKING_COMPANY = 'tracking_company';
    const TRACKING_NUMBERS = 'tracking_numbers';
    const TRACKING_URLS = 'tracking_urls';
    const LOCATION_ID = 'location_id';
    const UPDATED_AT = 'updated_at';
    const VARIANT_INVENTORY_MANAGEMENT = 'variant_inventory_management';
    const MERCHANT_REQUESTS = 'merchant_requests';

    public function getFieldTypes()
    {
        return array(
            'id' => 'string',
            'shop_id' => 'string',
            'order_id' => 'string',
            'assigned_location_id' => 'string',
            'request_status' => 'string',
            'status' => 'string',
            'supported_actions' => 'array',
            'destination' => 'AssignedFulfillmentOrderDestination',
            'line_items' => 'AssignedFulfillmentOrderLineItem[]',
            'merchant_requests' => 'FulfillmentOrderMerchantRequest[]',
            'fulfillment_service_handle' => 'string',
            'assigned_location' => 'AssignedFulfillmentOrderAssignedLocation'
        );
    }
}
