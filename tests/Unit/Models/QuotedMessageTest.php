<?php
// tests/Unit/Models/QuotedMessageTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Models\QuotedMessage;

class QuotedMessageTest extends TestCase
{
    /** @test */
    public function it_can_create_quoted_message_with_key_only()
    {
        $key = [
            'remoteJid' => '5511999999999@c.us',
            'fromMe' => false,
            'id' => '12345'
        ];

        $quotedMessage = new QuotedMessage($key);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertCount(1, $data);
        $this->assertArrayNotHasKey('message', $data);
    }

    /** @test */
    public function it_can_create_quoted_message_with_key_and_message()
    {
        $key = [
            'remoteJid' => '5511999999999@c.us',
            'fromMe' => true,
            'id' => '67890'
        ];

        $message = [
            'conversation' => 'Hello, this is the original message'
        ];

        $quotedMessage = new QuotedMessage($key, $message);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertEquals($message, $data['message']);
        $this->assertCount(2, $data);
    }

    /** @test */
    public function it_can_create_quoted_message_with_complex_message()
    {
        $key = [
            'remoteJid' => '5511888888888@c.us',
            'fromMe' => false,
            'id' => 'ABC123XYZ'
        ];

        $message = [
            'imageMessage' => [
                'url' => 'https://example.com/image.jpg',
                'caption' => 'Image caption',
                'mimetype' => 'image/jpeg'
            ]
        ];

        $quotedMessage = new QuotedMessage($key, $message);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertEquals($message, $data['message']);
        $this->assertArrayHasKey('imageMessage', $data['message']);
        $this->assertEquals('https://example.com/image.jpg', $data['message']['imageMessage']['url']);
    }

    /** @test */
    public function it_can_create_quoted_message_with_group_key()
    {
        $key = [
            'remoteJid' => '123456789@g.us',
            'fromMe' => false,
            'id' => 'GROUP_MSG_123',
            'participant' => '5511999999999@c.us'
        ];

        $message = [
            'conversation' => 'Group message content'
        ];

        $quotedMessage = new QuotedMessage($key, $message);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertEquals($message, $data['message']);
        $this->assertArrayHasKey('participant', $data['key']);
        $this->assertEquals('5511999999999@c.us', $data['key']['participant']);
    }

    /** @test */
    public function it_can_create_quoted_message_with_empty_key()
    {
        $key = [];
        $message = ['conversation' => 'Test message'];

        $quotedMessage = new QuotedMessage($key, $message);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertEquals($message, $data['message']);
        $this->assertIsArray($data['key']);
        $this->assertCount(0, $data['key']);
    }

    /** @test */
    public function it_can_create_quoted_message_with_empty_message()
    {
        $key = [
            'remoteJid' => '5511777777777@c.us',
            'fromMe' => true,
            'id' => 'EMPTY_MSG'
        ];

        $message = [];

        $quotedMessage = new QuotedMessage($key, $message);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertEquals($message, $data['message']);
        $this->assertIsArray($data['message']);
        $this->assertCount(0, $data['message']);
    }

    /** @test */
    public function it_handles_null_message_correctly()
    {
        $key = [
            'remoteJid' => '5511666666666@c.us',
            'fromMe' => false,
            'id' => 'NULL_MSG'
        ];

        $quotedMessage = new QuotedMessage($key, null);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertArrayNotHasKey('message', $data);
        $this->assertCount(1, $data);
    }

    /** @test */
    public function it_can_create_quoted_message_with_special_characters_in_key()
    {
        $key = [
            'remoteJid' => '5511555555555@c.us',
            'fromMe' => true,
            'id' => 'MSG_WITH_SPECIAL_CHARS_!@#$%',
            'pushName' => 'Nome com Acentos & Símbolos'
        ];

        $message = [
            'conversation' => 'Mensagem com caracteres especiais: áéíóú çñü'
        ];

        $quotedMessage = new QuotedMessage($key, $message);
        $data = $quotedMessage->toArray();

        $this->assertEquals($key, $data['key']);
        $this->assertEquals($message, $data['message']);
        $this->assertEquals('MSG_WITH_SPECIAL_CHARS_!@#$%', $data['key']['id']);
        $this->assertEquals('Nome com Acentos & Símbolos', $data['key']['pushName']);
    }

    /** @test */
    public function it_returns_correct_array_structure()
    {
        $key = ['test' => 'value'];
        $quotedMessage = new QuotedMessage($key);
        $data = $quotedMessage->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('key', $data);
    }

    /** @test */
    public function it_preserves_all_key_fields()
    {
        $key = [
            'remoteJid' => '5511444444444@c.us',
            'fromMe' => false,
            'id' => 'PRESERVE_TEST',
            'timestamp' => 1678901234,
            'participant' => '5511333333333@c.us',
            'pushName' => 'Test User',
            'customField' => 'custom value'
        ];

        $quotedMessage = new QuotedMessage($key);
        $data = $quotedMessage->toArray();

        foreach ($key as $field => $value) {
            $this->assertEquals($value, $data['key'][$field]);
        }
    }
}