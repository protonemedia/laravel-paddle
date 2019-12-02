<?php

namespace Protonemedia\LaravelPaddle\Tests;

use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;
use ProtoneMedia\LaravelPaddle\PaddleServiceProvider;

class WebhookTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PaddleServiceProvider::class];
    }

    private static function generatePrivateKey()
    {
        return openssl_pkey_new([
            'private_key_bits' => 512,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
    }

    public static function payload()
    {
        return [
            'alert_id'    => '123',
            'alert_name'  => 'subscription_created',
            'passthrough' => json_encode(['team_id' => 1]),
            'unit_price'  => '5.00',
            'user_id'     => '456',
        ];
    }

    /** @test */
    public function it_verifies_the_signature()
    {
        Event::fake();

        $keyDetails = openssl_pkey_get_details(static::generatePrivateKey());

        config(['paddle.public_key' => $keyDetails['key']]);

        $this->postJson('/paddle/webhook', static::payload() + ['p_signature' => 'invalid_signature'])
            ->assertStatus(403);

        Event::assertNotDispatched(SubscriptionCreated::class);
    }

    /** @test */
    public function it_maps_the_webhook_to_an_event_and_passes_the_data()
    {
        Event::fake();

        $privateKey = static::generatePrivateKey();
        $keyDetails = openssl_pkey_get_details($privateKey);

        config(['paddle.public_key' => $keyDetails['key']]);

        $webhookData = static::payload();

        openssl_sign(serialize($webhookData), $signature, $privateKey, OPENSSL_ALGO_SHA1);

        $this->postJson('/paddle/webhook', $webhookData + ['p_signature' => base64_encode($signature)])
            ->assertStatus(200);

        Event::assertDispatched(SubscriptionCreated::class, function ($event) {
            return $event->all() === static::payload();
        });
    }
}
