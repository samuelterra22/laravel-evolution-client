<?php
// tests/Unit/Models/LabelTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Models;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Models\Label;

class LabelTest extends TestCase
{
    /** @test */
    public function it_can_create_label_with_add_action()
    {
        $number = '5511999999999';
        $labelId = 'label_123';
        $action = 'add';

        $label = new Label($number, $labelId, $action);
        $data = $label->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertEquals($labelId, $data['labelId']);
        $this->assertEquals($action, $data['action']);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_can_create_label_with_remove_action()
    {
        $number = '5511888888888';
        $labelId = 'label_456';
        $action = 'remove';

        $label = new Label($number, $labelId, $action);
        $data = $label->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertEquals($labelId, $data['labelId']);
        $this->assertEquals($action, $data['action']);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_throws_exception_for_invalid_action()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Action must be 'add' or 'remove'");

        new Label('5511999999999', 'label_123', 'invalid');
    }

    /** @test */
    public function it_throws_exception_for_empty_action()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Action must be 'add' or 'remove'");

        new Label('5511999999999', 'label_123', '');
    }

    /** @test */
    public function it_throws_exception_for_case_sensitive_action()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Action must be 'add' or 'remove'");

        new Label('5511999999999', 'label_123', 'ADD');
    }

    /** @test */
    public function it_throws_exception_for_whitespace_action()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Action must be 'add' or 'remove'");

        new Label('5511999999999', 'label_123', ' add ');
    }

    /** @test */
    public function it_can_create_label_with_formatted_phone_number()
    {
        $number = '+55 (11) 99999-9999';
        $labelId = 'label_formatted';
        $action = 'add';

        $label = new Label($number, $labelId, $action);
        $data = $label->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertEquals($labelId, $data['labelId']);
        $this->assertEquals($action, $data['action']);
    }

    /** @test */
    public function it_can_create_label_with_special_characters_in_label_id()
    {
        $number = '5511777777777';
        $labelId = 'label_with_special_chars_@#$%';
        $action = 'remove';

        $label = new Label($number, $labelId, $action);
        $data = $label->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertEquals($labelId, $data['labelId']);
        $this->assertEquals($action, $data['action']);
    }

    /** @test */
    public function it_can_create_label_with_numeric_label_id()
    {
        $number = '5511666666666';
        $labelId = '12345';
        $action = 'add';

        $label = new Label($number, $labelId, $action);
        $data = $label->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertEquals($labelId, $data['labelId']);
        $this->assertEquals($action, $data['action']);
    }

    /** @test */
    public function it_can_create_label_with_empty_number()
    {
        $number = '';
        $labelId = 'label_empty_number';
        $action = 'add';

        $label = new Label($number, $labelId, $action);
        $data = $label->toArray();

        $this->assertEquals('', $data['number']);
        $this->assertEquals($labelId, $data['labelId']);
        $this->assertEquals($action, $data['action']);
    }

    /** @test */
    public function it_can_create_label_with_empty_label_id()
    {
        $number = '5511555555555';
        $labelId = '';
        $action = 'remove';

        $label = new Label($number, $labelId, $action);
        $data = $label->toArray();

        $this->assertEquals($number, $data['number']);
        $this->assertEquals('', $data['labelId']);
        $this->assertEquals($action, $data['action']);
    }

    /** @test */
    public function it_returns_correct_array_structure()
    {
        $label = new Label('123456789', 'test_label', 'add');
        $data = $label->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('number', $data);
        $this->assertArrayHasKey('labelId', $data);
        $this->assertArrayHasKey('action', $data);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_validates_action_on_construction()
    {
        // Test that valid actions work
        $validActions = ['add', 'remove'];
        
        foreach ($validActions as $action) {
            $label = new Label('123', 'label', $action);
            $this->assertEquals($action, $label->toArray()['action']);
        }

        // Test that invalid actions throw exception
        $invalidActions = ['delete', 'update', 'modify', 'edit', 'create'];
        
        foreach ($invalidActions as $action) {
            $this->expectException(InvalidArgumentException::class);
            new Label('123', 'label', $action);
        }
    }
}