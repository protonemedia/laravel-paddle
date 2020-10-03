<?php

namespace ProtoneMedia\LaravelPaddle\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelPaddle\Api\Api;
use ProtoneMedia\LaravelPaddle\Api\PaddleApiException;
use ProtoneMedia\LaravelPaddle\PaddleServiceProvider;

class ApiTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PaddleServiceProvider::class];
    }

    /** @test */
    public function it_throws_an_exception_if_the_request_was_unsuccessful()
    {
        Http::fake(function (Request $request) {
            $this->assertEquals('POST', $request->method());

            return Http::response(null, 500);
        });

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
        Http::fake(function (Request $request) {
            $this->assertEquals('POST', $request->method());

            return Http::response([
                'success' => false,
                'error'   => ['code' => 1336, 'message' => 'Whoops!'],
            ], 200);
        });

        try {
            $request = (new Api)->subscription()->listPlans()->send();
        } catch (PaddleApiException $exception) {
            return $this->assertEquals("[1336] Whoops!", $exception->getMessage());
        }

        $this->fail('Should have thrown PaddleApiException');
    }

    /** @test */
    public function it_returns_the_complete_json_response_if_the_response_key_doesnt_exist()
    {
        Http::fake(function (Request $request) {
            $this->assertEquals('POST', $request->method());

            return Http::response([
                'key' => 'value',
            ], 200);
        });

        $response = (new Api)->subscription()->listPlans()->send();

        $this->assertEquals(['key' => 'value'], $response);
    }

    /** @test */
    public function it_throws_an_exception_if_the_success_attribute_is_false()
    {
        Http::fake(function (Request $request) {
            $this->assertEquals('POST', $request->method());

            return Http::response([
                'success' => false,
            ], 200);
        });

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

        Http::fake(function (Request $request) {
            $this->assertEquals('POST', $request->method());
            $this->assertEquals('https://vendors.paddle.com/api/2.0/product/generate_pay_link', $request->url());
            $this->assertEquals([
                'vendor_id'        => 20,
                'vendor_auth_code' => 30,
                'product_id'       => 10,
            ], $request->data());

            return Http::response([
                'success'  => true,
                'response' => 'Hello!',
            ], 200);
        });

        $response = (new Api)->product()->generatePayLink([
            'product_id' => 10,
        ])->send();
    }

    /** @test */
    public function it_returns_the_response_attribute_if_the_request_was_successful()
    {
        Http::fake(function (Request $request) {
            $this->assertEquals('POST', $request->method());

            return Http::response([
                'success'  => true,
                'response' => 'Hello!',
            ], 200);
        });

        $response = (new Api)->subscription()->listPlans()->send();

        $this->assertEquals('Hello!', $response);
    }

    /** @test */
    public function it_can_make_a_get_request()
    {
        Http::fake(function (Request $request) {
            $this->assertEquals('GET', $request->method());
            $this->assertFalse(array_key_exists('vendor_auth_code', $request->data()));

            return Http::response([
                'success'  => true,
                'response' => 'Hello!',
            ], 200);
        });

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
    public function it_has_a_custom_host_for_the_checkouts_request()
    {
        $request = (new Api)->checkout()->getOrderDetails();
        $this->assertEquals('https://checkout.paddle.com/api/1.0/order', $request->url());

        $request = (new Api)->checkout()->getUserHistory();
        $this->assertEquals('https://checkout.paddle.com/api/2.0/user/history', $request->url());

        $request = (new Api)->checkout()->getPrices();
        $this->assertEquals('https://checkout.paddle.com/api/2.0/prices', $request->url());
    }
}
