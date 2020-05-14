<?php

namespace ProtoneMedia\LaravelPaddle\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelPaddle\Api\Api;
use ProtoneMedia\LaravelPaddle\Api\PaddleApiException;
use ProtoneMedia\LaravelPaddle\PaddleServiceProvider;
use Zttp\PendingZttpRequest;

class ApiTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PaddleServiceProvider::class];
    }

    private function mockZttp(): PendingZttpRequest
    {
        return tap(Mockery::mock(PendingZttpRequest::class), function ($zttp) {
            $zttp->shouldReceive('asFormParams')->andReturnSelf();

            $this->app->singleton('laravel-paddle.http', function () use ($zttp) {
                return $zttp;
            });
        });
    }

    /** @test */
    public function it_throws_an_exception_if_the_request_was_unsuccessful()
    {
        $zttp = $this->mockZttp();
        $zttp->shouldReceive('post')->andReturnSelf();
        $zttp->shouldReceive('isSuccess')->andReturnFalse();
        $zttp->shouldReceive('status')->andReturn(500);

        try {
            $request = (new Api)->subscription()->listPlans()->send();
        } catch (PaddleApiException $exception) {
            return $this->assertEquals("Response with status code 500", $exception->getMessage());
        }

        $this->fail('Should have thrown PaddleApiException');
    }

    /** @test */
    public function it_throws_an_exception_if_the_success_attribute_is_false_and_an_error_object_is_given()
    {
        $zttp = $this->mockZttp();
        $zttp->shouldReceive('post')->andReturnSelf();
        $zttp->shouldReceive('isSuccess')->andReturnTrue();
        $zttp->shouldReceive('json')->andReturn([
            'success' => false,
            'error'   => ['code' => 1336, 'message' => 'Whoops!'],
        ]);

        try {
            $request = (new Api)->subscription()->listPlans()->send();
        } catch (PaddleApiException $exception) {
            return $this->assertEquals("[1336] Whoops!", $exception->getMessage());
        }

        $this->fail('Should have thrown PaddleApiException');
    }

    /** @test */
    public function it_throws_an_exception_if_the_success_attribute_is_false()
    {
        $zttp = $this->mockZttp();
        $zttp->shouldReceive('post')->andReturnSelf();
        $zttp->shouldReceive('isSuccess')->andReturnTrue();
        $zttp->shouldReceive('json')->andReturn([
            'success' => false,
        ]);

        try {
            $request = (new Api)->subscription()->listPlans()->send();
        } catch (PaddleApiException $exception) {
            return $this->assertEquals("Paddle API request was unsuccessful and no error code/message was returned", $exception->getMessage());
        }

        $this->fail('Should have thrown PaddleApiException');
    }

    /** @test */
    public function it_formats_the_uri_and_posts_the_config_with_the_payload()
    {
        config([
            'paddle' => [
                'vendor_id'        => 20,
                'vendor_auth_code' => 30,
            ],
        ]);

        $zttp = $this->mockZttp();

        $zttp->shouldReceive('post')->withArgs(function ($url, $data) {
            $this->assertEquals('https://vendors.paddle.com/api/2.0/product/generate_pay_link', $url);
            $this->assertEquals([
                'vendor_id'        => 20,
                'vendor_auth_code' => 30,
                'product_id'       => 10,
            ], $data);

            return true;
        })->andReturnSelf();

        $zttp->shouldReceive('isSuccess')->andReturnTrue();
        $zttp->shouldReceive('json')->andReturn([
            'success'  => true,
            'response' => 'Hello!',
        ]);

        $response = (new Api)->product()->generatePayLink([
            'product_id' => 10,
        ])->send();
    }

    /** @test */
    public function it_returns_the_response_attribute_if_the_request_was_successful()
    {
        $zttp = $this->mockZttp();
        $zttp->shouldReceive('post')->andReturnSelf();
        $zttp->shouldReceive('isSuccess')->andReturnTrue();
        $zttp->shouldReceive('json')->andReturn([
            'success'  => true,
            'response' => 'Hello!',
        ]);

        $response = (new Api)->subscription()->listPlans()->send();

        $this->assertEquals('Hello!', $response);
    }

    /** @test */
    public function it_can_make_a_get_request()
    {
        $zttp = $this->mockZttp();
        $zttp->shouldReceive('get')->withArgs(function ($url, $query) {
            $this->assertFalse(array_key_exists('vendor_auth_code', $query));

            return true;
        })->andReturnSelf();
        $zttp->shouldReceive('isSuccess')->andReturnTrue();
        $zttp->shouldReceive('json')->andReturn([
            'success'  => true,
            'response' => 'Hello!',
        ]);

        $response = (new Api)->checkout()->getUserHistory([
            'email' => 'test@example',
        ])->send();

        $this->assertEquals('Hello!', $response);
    }

    /** @test */
    public function it_can_list_transactions_for_an_entity()
    {
        $request = (new Api)->product()->listTransactions('user', 123);

        $this->assertEquals('https://vendors.paddle.com/api/2.0/user/123/transactions', $request->url());
    }

    /** @test */
    public function it_can_create_an_one_off_chrage()
    {
        $request = (new Api)->subscription()->createOneOffCharge('123');

        $this->assertEquals('https://vendors.paddle.com/api/2.0/subscription/123/charge', $request->url());
    }

    /** @test */
    public function it_has_a_custom_host_for_the_order_details_request()
    {
        $request = (new Api)->checkout()->getOrderDetails();

        $this->assertEquals('https://checkout.paddle.com/api/1.0/order', $request->url());
    }
}
