<?php
// tests/Unit/Exceptions/EvolutionApiExceptionTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Exceptions;

use Exception;
use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Exceptions\EvolutionApiException;

class EvolutionApiExceptionTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_default_parameters()
    {
        $exception = new EvolutionApiException();

        $this->assertInstanceOf(EvolutionApiException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals('', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    /** @test */
    public function it_can_be_instantiated_with_message_only()
    {
        $message = 'API request failed';
        $exception = new EvolutionApiException($message);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    /** @test */
    public function it_can_be_instantiated_with_message_and_code()
    {
        $message = 'Unauthorized';
        $code = 401;
        $exception = new EvolutionApiException($message, $code);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    /** @test */
    public function it_can_be_instantiated_with_all_parameters()
    {
        $message = 'Server error';
        $code = 500;
        $previous = new Exception('Original exception');
        $exception = new EvolutionApiException($message, $code, $previous);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /** @test */
    public function it_extends_exception_class()
    {
        $exception = new EvolutionApiException();

        $this->assertInstanceOf(Exception::class, $exception);
    }

    /** @test */
    public function it_can_handle_various_error_codes()
    {
        $testCases = [
            ['Bad Request', 400],
            ['Unauthorized', 401],
            ['Forbidden', 403],
            ['Not Found', 404],
            ['Method Not Allowed', 405],
            ['Internal Server Error', 500],
            ['Bad Gateway', 502],
            ['Service Unavailable', 503],
        ];

        foreach ($testCases as [$message, $code]) {
            $exception = new EvolutionApiException($message, $code);
            $this->assertEquals($message, $exception->getMessage());
            $this->assertEquals($code, $exception->getCode());
        }
    }

    /** @test */
    public function it_can_handle_special_characters_in_message()
    {
        $message = 'Erro na API: Caracteres especiais áéíóú çñü @#$%^&*()';
        $exception = new EvolutionApiException($message);

        $this->assertEquals($message, $exception->getMessage());
    }

    /** @test */
    public function it_can_handle_long_error_messages()
    {
        $message = str_repeat('This is a very long error message that might occur in real world scenarios. ', 10);
        $exception = new EvolutionApiException($message);

        $this->assertEquals($message, $exception->getMessage());
    }

    /** @test */
    public function it_can_handle_empty_string_message()
    {
        $exception = new EvolutionApiException('');

        $this->assertEquals('', $exception->getMessage());
    }

    /** @test */
    public function it_can_handle_zero_error_code()
    {
        $exception = new EvolutionApiException('Test message', 0);

        $this->assertEquals('Test message', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }

    /** @test */
    public function it_can_handle_negative_error_code()
    {
        $exception = new EvolutionApiException('Test message', -1);

        $this->assertEquals('Test message', $exception->getMessage());
        $this->assertEquals(-1, $exception->getCode());
    }

    /** @test */
    public function it_preserves_previous_exception_chain()
    {
        $originalException = new Exception('Original error');
        $middleException = new Exception('Middle error', 0, $originalException);
        $evolutionException = new EvolutionApiException('Evolution API error', 500, $middleException);

        $this->assertSame($middleException, $evolutionException->getPrevious());
        $this->assertSame($originalException, $evolutionException->getPrevious()->getPrevious());
    }

    /** @test */
    public function it_can_be_thrown_and_caught()
    {
        $message = 'Test exception';
        $code = 400;

        try {
            throw new EvolutionApiException($message, $code);
        } catch (EvolutionApiException $e) {
            $this->assertEquals($message, $e->getMessage());
            $this->assertEquals($code, $e->getCode());
        }
    }

    /** @test */
    public function it_can_be_caught_as_generic_exception()
    {
        $message = 'Test exception';
        $code = 500;

        try {
            throw new EvolutionApiException($message, $code);
        } catch (Exception $e) {
            $this->assertInstanceOf(EvolutionApiException::class, $e);
            $this->assertEquals($message, $e->getMessage());
            $this->assertEquals($code, $e->getCode());
        }
    }

    /** @test */
    public function it_provides_string_representation()
    {
        $message = 'API Error';
        $code = 400;
        $exception = new EvolutionApiException($message, $code);

        $stringRep = (string) $exception;
        $this->assertStringContainsString($message, $stringRep);
        $this->assertStringContainsString('EvolutionApiException', $stringRep);
        $this->assertEquals($code, $exception->getCode());
        $this->assertEquals($message, $exception->getMessage());
    }
}