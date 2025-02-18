<?php

namespace ProtoneMedia\LaravelPaddle\Tests;

use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelPaddle\PaddleServiceProvider;

class DirectiveTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PaddleServiceProvider::class];
    }

    /** @test */
    public function it_includes_paddle_js_with_the_vendor_id()
    {
        config([
            'paddle' => [
                'vendor_id' => 20,
            ],
        ]);

        View::addLocation(__DIR__);

        $rendered = (string) view('dummy');

        $this->assertStringNotContainsString("Paddle.Environment.set('sandbox');", $rendered);
        $this->assertStringContainsString('Paddle.Setup({"vendor":20});', $rendered);
    }

    /** @test */
    public function it_includes_paddle_js_with_the_sandbox_environment()
    {
        config([
            'paddle' => [
                'vendor_id'           => 20,
                'sandbox_environment' => true,
            ],
        ]);

        View::addLocation(__DIR__);

        $rendered = (string) view('dummy');

        $this->assertStringContainsString("Paddle.Environment.set('sandbox');", $rendered);
        $this->assertStringContainsString('Paddle.Setup({"vendor":20});', $rendered);
    }

    /** @test */
    public function it_includes_paddle_js_with_vendor_id()
    {
        View::addLocation(__DIR__);
        config([
            'paddle' => [
                'vendor_id' => 20,
            ],
        ]);

        $rendered = (string) view('dummy-vendor', ['vendor' => 50]);

        $this->assertStringContainsString('Paddle.Setup({"vendor":50});', $rendered);
    }
}
