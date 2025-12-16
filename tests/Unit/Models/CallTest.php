<?php
// tests/Unit/Models/CallTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Models\Call;

class CallTest extends TestCase
{
    /** @test */
    public function it_can_create_voice_call()
    {
        $number = '5511999999999';
        $isVideo = false;
        $callDuration = 45;

        $call = new Call($number, $isVideo, $callDuration);
        $data = $call->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertFalse($data['isVideo']);
        $this->assertEquals($callDuration, $data['callDuration']);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_can_create_video_call()
    {
        $number = '5511888888888';
        $isVideo = true;
        $callDuration = 120;

        $call = new Call($number, $isVideo, $callDuration);
        $data = $call->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertTrue($data['isVideo']);
        $this->assertEquals($callDuration, $data['callDuration']);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_can_create_call_with_zero_duration()
    {
        $number = '5511777777777';
        $isVideo = false;
        $callDuration = 0;

        $call = new Call($number, $isVideo, $callDuration);
        $data = $call->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertFalse($data['isVideo']);
        $this->assertEquals(0, $data['callDuration']);
    }

    /** @test */
    public function it_can_create_call_with_long_duration()
    {
        $number = '5511666666666';
        $isVideo = true;
        $callDuration = 3600; // 1 hour

        $call = new Call($number, $isVideo, $callDuration);
        $data = $call->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertTrue($data['isVideo']);
        $this->assertEquals(3600, $data['callDuration']);
    }

    /** @test */
    public function it_can_create_call_with_formatted_phone_number()
    {
        $number = '+55 (11) 99999-9999';
        $isVideo = false;
        $callDuration = 30;

        $call = new Call($number, $isVideo, $callDuration);
        $data = $call->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertFalse($data['isVideo']);
        $this->assertEquals($callDuration, $data['callDuration']);
    }

    /** @test */
    public function it_can_create_call_with_international_number()
    {
        $number = '+1234567890';
        $isVideo = true;
        $callDuration = 90;

        $call = new Call($number, $isVideo, $callDuration);
        $data = $call->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertTrue($data['isVideo']);
        $this->assertEquals($callDuration, $data['callDuration']);
    }

    /** @test */
    public function it_returns_correct_array_structure()
    {
        $call = new Call('123456789', false, 60);
        $data = $call->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('number', $data);
        $this->assertArrayHasKey('isVideo', $data);
        $this->assertArrayHasKey('callDuration', $data);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_preserves_boolean_type_for_is_video()
    {
        $voiceCall = new Call('123', false, 30);
        $voiceData = $voiceCall->toArray();

        $videoCall = new Call('456', true, 60);
        $videoData = $videoCall->toArray();

        $this->assertIsBool($voiceData['isVideo']);
        $this->assertIsBool($videoData['isVideo']);
        $this->assertFalse($voiceData['isVideo']);
        $this->assertTrue($videoData['isVideo']);
    }

    /** @test */
    public function it_preserves_integer_type_for_call_duration()
    {
        $call = new Call('123456789', true, 45);
        $data = $call->toArray();

        $this->assertIsInt($data['callDuration']);
        $this->assertEquals(45, $data['callDuration']);
    }

    /** @test */
    public function it_can_create_call_with_empty_number()
    {
        $number = '';
        $isVideo = false;
        $callDuration = 10;

        $call = new Call($number, $isVideo, $callDuration);
        $data = $call->toArray();

        $this->assertEquals('', $data['number']);
        $this->assertFalse($data['isVideo']);
        $this->assertEquals($callDuration, $data['callDuration']);
    }
}