<?php

namespace ProtoneMedia\LaravelPaddle\Tests;

use Illuminate\Support\Facades\Event as EventFacade;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelPaddle\Events\Event;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;

class EventTest extends TestCase
{
    /** @test */
    public function it_can_have_payload_that_is_accessible()
    {
        $event = new SubscriptionCreated([
            'product_id'     => 10,
            'customer_email' => 'test@example.com',
            'passthrough'    => json_encode(['team_id' => 20]),
        ]);

        $this->assertEquals(10, $event->product_id);
        $this->assertEquals('test@example.com', $event->customer_email);
        $this->assertEquals(['team_id' => 20], $event->passthrough);
    }

    /** @test */
    public function it_can_fire_an_event_based_on_the_action_name()
    {
        EventFacade::fake();

        Event::fire($data = [
            'alert_id'   => '10',
            'alert_name' => 'subscription_created',
        ]);

        EventFacade::assertDispatched(SubscriptionCreated::class, function ($event) {
            return $event->alert_id === '10';
        });
    }
}
