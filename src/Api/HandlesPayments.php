<?php

namespace ProtoneMedia\LaravelPaddle\Api;

trait HandlesPayments
{
    /**
     * https://developer.paddle.com/api-reference/subscription-api/payments/listpayments
     */
    public function listPayments(array $data = [])
    {
        return new Request('/2.0/subscription/payments', $data);
    }

    /**
     * https://developer.paddle.com/api-reference/subscription-api/payments/updatepayment
     */
    public function reschedulePayment(array $data = [])
    {
        return new Request('/2.0/subscription/payments_reschedule', $data, [
            'payment_id' => 'required|integer',
            'date'       => 'required',
        ]);
    }
}
