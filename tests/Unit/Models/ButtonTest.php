<?php
// tests/Unit/Models/ButtonTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Models\Button;

class ButtonTest extends TestCase
{
    /** @test */
    public function it_can_create_reply_button()
    {
        $type        = 'reply';
        $displayText = 'Yes';
        $attributes  = ['id' => 'btn-yes'];

        $button = new Button($type, $displayText, $attributes);
        $data   = $button->toArray();

        $this->assertEquals($type, $data['type']);
        $this->assertEquals($displayText, $data['displayText']);
        $this->assertEquals('btn-yes', $data['id']);
    }

    /** @test */
    public function it_can_create_url_button()
    {
        $type        = 'url';
        $displayText = 'Visit Website';
        $attributes  = ['url' => 'https://example.com'];

        $button = new Button($type, $displayText, $attributes);
        $data   = $button->toArray();

        $this->assertEquals($type, $data['type']);
        $this->assertEquals($displayText, $data['displayText']);
        $this->assertEquals('https://example.com', $data['url']);
    }

    /** @test */
    public function it_can_create_call_button()
    {
        $type        = 'call';
        $displayText = 'Call Now';
        $attributes  = ['phoneNumber' => '+5511999999999'];

        $button = new Button($type, $displayText, $attributes);
        $data   = $button->toArray();

        $this->assertEquals($type, $data['type']);
        $this->assertEquals($displayText, $data['displayText']);
        $this->assertEquals('+5511999999999', $data['phoneNumber']);
    }

    /** @test */
    public function it_can_create_button_without_attributes()
    {
        $type        = 'reply';
        $displayText = 'Simple Button';

        $button = new Button($type, $displayText);
        $data   = $button->toArray();

        $this->assertEquals($type, $data['type']);
        $this->assertEquals($displayText, $data['displayText']);
        $this->assertCount(2, $data);
    }

    /** @test */
    public function it_can_create_button_with_multiple_attributes()
    {
        $type        = 'reply';
        $displayText = 'Complex Button';
        $attributes  = [
            'id'       => 'btn-complex',
            'payload'  => 'complex_action',
            'metadata' => ['priority' => 'high'],
        ];

        $button = new Button($type, $displayText, $attributes);
        $data   = $button->toArray();

        $this->assertEquals($type, $data['type']);
        $this->assertEquals($displayText, $data['displayText']);
        $this->assertEquals('btn-complex', $data['id']);
        $this->assertEquals('complex_action', $data['payload']);
        $this->assertEquals(['priority' => 'high'], $data['metadata']);
        $this->assertCount(5, $data);
    }

    /** @test */
    public function it_can_create_button_with_empty_attributes_array()
    {
        $type        = 'reply';
        $displayText = 'Empty Attributes';
        $attributes  = [];

        $button = new Button($type, $displayText, $attributes);
        $data   = $button->toArray();

        $this->assertEquals($type, $data['type']);
        $this->assertEquals($displayText, $data['displayText']);
        $this->assertCount(2, $data);
    }

    /** @test */
    public function it_merges_attributes_correctly()
    {
        $type        = 'url';
        $displayText = 'Test Button';
        $attributes  = [
            'url'         => 'https://test.com',
            'customField' => 'custom_value',
        ];

        $button = new Button($type, $displayText, $attributes);
        $data   = $button->toArray();

        // Should have constructor parameters and merged attributes
        $this->assertEquals('url', $data['type']);
        $this->assertEquals('Test Button', $data['displayText']);
        $this->assertEquals('https://test.com', $data['url']);
        $this->assertEquals('custom_value', $data['customField']);
        $this->assertCount(4, $data);
    }
}
