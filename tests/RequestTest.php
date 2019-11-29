<?php

namespace Protonemedia\LaravelPaddle\Tests;

use PHPUnit\Framework\TestCase;
use ProtoneMedia\LaravelPaddle\Api\Api;

class RequestTest extends TestCase
{
    /** @test */
    public function it_can_set_the_request_payload_fluently()
    {
        $request = (new Api)->product()
            ->generatePayLink()
            ->productId(10)
            ->customerEmail('test@example.com')
            ->passthrough(['team_id' => 20]);

        $this->assertEquals([
            'product_id'     => 10,
            'customer_email' => 'test@example.com',
            'passthrough'    => json_encode(['team_id' => 20]),
        ], $request->getData());
    }

    /** @test */
    public function it_can_set_the_request_payload_by_array()
    {
        $request = (new Api)->product()->generatePayLink([
            'product_id'     => 10,
            'customer_email' => 'test@example.com',
            'passthrough'    => ['team_id' => 20],
        ]);

        $this->assertEquals([
            'product_id'     => 10,
            'customer_email' => 'test@example.com',
            'passthrough'    => json_encode(['team_id' => 20]),
        ], $request->getData());
    }
}
