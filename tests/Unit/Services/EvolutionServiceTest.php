<?php
// tests/Unit/Services/EvolutionServiceTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;

class EvolutionServiceTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $service = new EvolutionService('http://localhost:8080', 'test-api-key', 30);

        $this->assertInstanceOf(EvolutionService::class, $service);
        $this->assertEquals('http://localhost:8080', $service->getBaseUrl());
        $this->assertEquals('test-api-key', $service->getApiKey());
        $this->assertInstanceOf(Client::class, $service->getClient());
    }

    /** @test */
    public function it_trims_base_url()
    {
        $service = new EvolutionService('http://localhost:8080/', 'test-api-key');

        $this->assertEquals('http://localhost:8080', $service->getBaseUrl());
    }

    /** @test */
    public function it_can_make_get_request()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success', 'data' => 'test']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->get('/test-endpoint');

        $this->assertEquals(['status' => 'success', 'data' => 'test'], $result);
    }

    /** @test */
    public function it_can_make_get_request_with_query_params()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->get('/test-endpoint', ['param1' => 'value1', 'param2' => 'value2']);

        $this->assertEquals(['status' => 'success'], $result);
    }

    /** @test */
    public function it_can_make_post_request()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success', 'id' => 123]))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->post('/test-endpoint', ['name' => 'test']);

        $this->assertEquals(['status' => 'success', 'id' => 123], $result);
    }

    /** @test */
    public function it_can_make_put_request()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'updated']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->put('/test-endpoint', ['name' => 'updated']);

        $this->assertEquals(['status' => 'updated'], $result);
    }

    /** @test */
    public function it_can_make_delete_request()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'deleted']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->delete('/test-endpoint', ['force' => true]);

        $this->assertEquals(['status' => 'deleted'], $result);
    }

    /** @test */
    public function it_throws_exception_for_invalid_json()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], 'invalid json')
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);

        $this->expectException(EvolutionApiException::class);
        $this->expectExceptionMessage('Invalid JSON response from API');
        $this->expectExceptionCode(500);

        $service->get('/test-endpoint');
    }

    /** @test */
    public function it_throws_exception_for_api_error_response()
    {
        $mockHandler = new MockHandler([
            new Response(400, [], json_encode([
                'error' => 'Bad Request',
                'code' => 400
            ]))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);

        $this->expectException(EvolutionApiException::class);
        $this->expectExceptionMessage('Bad Request');
        $this->expectExceptionCode(400);

        $service->get('/test-endpoint');
    }

    /** @test */
    public function it_throws_exception_for_status_error_response()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'error',
                'message' => 'Something went wrong'
            ]))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);

        $this->expectException(EvolutionApiException::class);
        $this->expectExceptionMessage('Something went wrong');

        $service->get('/test-endpoint');
    }

    /** @test */
    public function it_handles_guzzle_exceptions()
    {
        $mockHandler = new MockHandler([
            new RequestException(
                'Connection timeout',
                new Request('GET', 'test'),
                new Response(500, [], json_encode(['error' => 'Server Error']))
            )
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);

        $this->expectException(EvolutionApiException::class);
        $this->expectExceptionMessage('Server Error');

        $service->get('/test-endpoint');
    }

    /** @test */
    public function it_handles_guzzle_exceptions_without_response()
    {
        $mockHandler = new MockHandler([
            new RequestException(
                'Network error',
                new Request('GET', 'test')
            )
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);

        $this->expectException(EvolutionApiException::class);
        $this->expectExceptionMessage('Network error');

        $service->get('/test-endpoint');
    }

    /** @test */
    public function it_returns_empty_array_for_null_response()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(null))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->get('/test-endpoint');

        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_trims_leading_slash_from_endpoint()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->get('/test-endpoint');

        $this->assertEquals(['status' => 'success'], $result);
    }

    /** @test */
    public function it_can_make_post_request_with_empty_data()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->post('/test-endpoint');

        $this->assertEquals(['status' => 'success'], $result);
    }

    /** @test */
    public function it_can_make_put_request_with_empty_data()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->put('/test-endpoint');

        $this->assertEquals(['status' => 'success'], $result);
    }

    /** @test */
    public function it_can_make_delete_request_with_empty_params()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success']))
        ]);

        $service = $this->createServiceWithMockHandler($mockHandler);
        $result = $service->delete('/test-endpoint');

        $this->assertEquals(['status' => 'success'], $result);
    }

    /** @test */
    public function it_sets_correct_headers()
    {
        $service = new EvolutionService('http://localhost:8080', 'test-key');
        $client = $service->getClient();
        $config = $client->getConfig();

        $this->assertEquals('application/json', $config['headers']['Content-Type']);
        $this->assertEquals('application/json', $config['headers']['Accept']);
        $this->assertEquals('test-key', $config['headers']['apikey']);
    }

    /** @test */
    public function it_sets_custom_timeout()
    {
        $service = new EvolutionService('http://localhost:8080', 'test-key', 60);
        $client = $service->getClient();
        $config = $client->getConfig();

        $this->assertEquals(60, $config['timeout']);
    }

    protected function createServiceWithMockHandler(MockHandler $mockHandler): EvolutionService
    {
        $handlerStack = HandlerStack::create($mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $service = new EvolutionService('http://localhost:8080', 'test-api-key');
        
        // Replace the client with our mocked one
        $reflection = new \ReflectionClass($service);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($service, $client);

        return $service;
    }
}