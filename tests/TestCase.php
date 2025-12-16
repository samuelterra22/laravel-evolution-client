<?php
// tests/TestCase.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SamuelTerra22\LaravelEvolutionClient\EvolutionServiceProvider;
use SamuelTerra22\LaravelEvolutionClient\Facades\Evolution;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var mixed
     */
    public static $latestResponse;

    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var EvolutionService
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        if (! defined('PHPUNIT_RUNNING')) {
            define('PHPUNIT_RUNNING', true);
        }

        $this->mockHandler = new MockHandler([
            new Response(200, [], json_encode([
                'status'  => 'success',
                'message' => 'Mock response',
            ])),
        ]);

        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient   = new Client(['handler' => $handlerStack]);

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->setConstructorArgs(['http://localhost:8080', 'test-api-key', 30])
            ->onlyMethods(['getClient'])
            ->getMock();

        $this->service->method('getClient')->willReturn($httpClient);
    }

    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            EvolutionServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Evolution' => Evolution::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('evolution.base_url', 'http://localhost:8080');
        $app['config']->set('evolution.api_key', 'test-api-key');
        $app['config']->set('evolution.default_instance', 'test-instance');
    }

    /**
     * Add a mock response to the handler.
     *
     * @param array $body
     * @param int   $status
     * @param array $headers
     *
     * @return self
     */
    protected function addMockResponse(array $body = [], int $status = 200, array $headers = [])
    {
        $this->mockHandler->append(
            new Response($status, $headers, json_encode($body))
        );

        return $this;
    }

    /**
     * Create a mocked service for testing.
     *
     * @return EvolutionService
     */
    protected function createMockService()
    {
        $mockResponse = [
            'status'  => 'success',
            'message' => 'Mock response',
        ];

        $mockedService = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockedService->method('get')
            ->willReturn($mockResponse);

        $mockedService->method('post')
            ->willReturn($mockResponse);

        $mockedService->method('put')
            ->willReturn($mockResponse);

        $mockedService->method('delete')
            ->willReturn($mockResponse);

        $mockedService->method('getBaseUrl')
            ->willReturn('http://localhost:8080');

        $mockedService->method('getApiKey')
            ->willReturn('test-api-key');

        return $mockedService;
    }
}
