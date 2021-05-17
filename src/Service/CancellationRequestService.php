<?php

namespace Shopify\Service;

use Shopify\Object\Fulfillment;

class CancellationRequestService extends AbstractService
{
    public function acceptCancellationRequest(string $fulfillmentOrderId, $message = "")
    {
        $cancellationRequest = new \stdClass();
        $cancellationRequest->message = $message;

        $endpoint = 'fulfillment_orders/'.$fulfillmentOrderId.'/cancellation_request/accept.json';
        $response = $this->request($endpoint, 'POST',["cancellation_request" => $cancellationRequest]);
    }

    public function rejectCancellationRequest(string $fulfillmentOrderId, $message = "")
    {
        $cancellationRequest = new \stdClass();
        $cancellationRequest->message = $message;

        $endpoint = 'fulfillment_orders/'.$fulfillmentOrderId.'/cancellation_request/reject.json';
        $response = $this->request($endpoint, 'POST',["cancellation_request" => $cancellationRequest]);
    }
}
