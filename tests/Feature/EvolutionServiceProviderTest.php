<?php
// tests/Feature/EvolutionServiceProviderTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Feature;

use Orchestra\Testbench\TestCase;
use SamuelTerra22\LaravelEvolutionClient\EvolutionApiClient;
use SamuelTerra22\LaravelEvolutionClient\EvolutionServiceProvider;
use SamuelTerra22\LaravelEvolutionClient\Facades\Evolution;

class EvolutionServiceProviderTest extends TestCase
{
    /**
     * @var mixed
     */
    public static $latestResponse;

    /** @test */
    public function it_registers_the_service()
    {
        $this->assertTrue($this->app->bound('evolution'));
        $this->assertInstanceOf(EvolutionApiClient::class, $this->app->make('evolution'));
    }

    /** @test */
    public function it_loads_the_config()
    {
        $this->assertEquals('http://localhost:8080', config('evolution.base_url'));
        $this->assertEquals('test-api-key', config('evolution.api_key'));
        $this->assertEquals('test-instance', config('evolution.default_instance'));
    }

    /** @test */
    public function the_facade_works()
    {
        $this->assertInstanceOf(EvolutionApiClient::class, app('evolution'));
    }

    /** @test */
    public function the_facade_provides_access_to_all_resources()
    {
        $client = app('evolution');
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Chat', $client->chat);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Group', $client->group);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Message', $client->message);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Instance', $client->instance);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Call', $client->call);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Label', $client->label);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Profile', $client->profile);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\WebSocket', $client->websocket);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Proxy', $client->proxy);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Settings', $client->settings);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Template', $client->template);
    }

    /** @test */
    public function it_publishes_the_config()
    {
        $this->artisan('vendor:publish', [
            '--provider' => 'SamuelTerra22\LaravelEvolutionClient\EvolutionServiceProvider',
            '--tag'      => 'evolution-config',
        ]);

        $this->assertFileExists(config_path('evolution.php'));
    }

    protected function getPackageProviders($app)
    {
        return [
            EvolutionServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Evolution' => Evolution::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Configurations for tests
        $app['config']->set('evolution.base_url', 'http://localhost:8080');
        $app['config']->set('evolution.api_key', 'test-api-key');
        $app['config']->set('evolution.default_instance', 'test-instance');
    }
}
