<?php

namespace ProtoneMedia\LaravelPaddle\Tests;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelPaddle\Api\Api;
use ProtoneMedia\LaravelPaddle\Api\InvalidDataException;
use ProtoneMedia\LaravelPaddle\Api\Request;

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

    /** @test */
    public function it_validates_the_given_data()
    {
        $request = (new Api)->checkout()->getUserHistory([
            'email'      => 'test@example.com',
            'product_id' => '123',
        ]);

        $this->assertInstanceOf(Request::class, $request);
    }

    /** @test */
    public function it_throws_an_exception_if_the_given_data_is_invalid()
    {
        try {
            $request = (new Api)->checkout()->getUserHistory([
                'email'      => 'nope',
                'product_id' => 'abc',
            ])->send();
        } catch (InvalidDataException $exception) {
            return $this->assertEquals(
                ['email', 'product_id'],
                $exception->getMessages()->keys()
            );
        }

        $this->fail('Should have thrown InvalidDataException');
    }

    /** @test */
    public function it_can_set_the_vendor_keys_into_payload_by_array()
    {
        config([
            'paddle' => [
                'vendor_id' => 20,
                'vendor_auth_code' => '123',
            ],
        ]);
        $request = (new Api)->product()->generatePayLink([
                'product_id'       => 10,
                'customer_email'   => 'test@example.com',
                'passthrough'      => ['team_id' => 20],
                'vendor_id'        => 50,
                'vendor_auth_code' => 'efg'
            ]);

        $this->assertEquals([
                'product_id'       => 10,
                'customer_email'   => 'test@example.com',
                'passthrough'      => json_encode(['team_id' => 20]),
                'vendor_id'        => 50,
                'vendor_auth_code' => 'efg'

            ], $request->getData());
    }
}
