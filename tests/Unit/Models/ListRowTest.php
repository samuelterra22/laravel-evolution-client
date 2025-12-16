<?php
// tests/Unit/Models/ListRowTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Models\ListRow;

class ListRowTest extends TestCase
{
    /** @test */
    public function it_can_create_list_row()
    {
        $title = 'Option 1';
        $description = 'Description for option 1';
        $rowId = 'opt1';

        $listRow = new ListRow($title, $description, $rowId);
        $data = $listRow->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertEquals($description, $data['description']);
        $this->assertEquals($rowId, $data['rowId']);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_can_create_list_row_with_empty_description()
    {
        $title = 'Option 2';
        $description = '';
        $rowId = 'opt2';

        $listRow = new ListRow($title, $description, $rowId);
        $data = $listRow->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertEquals('', $data['description']);
        $this->assertEquals($rowId, $data['rowId']);
    }

    /** @test */
    public function it_can_create_list_row_with_long_text()
    {
        $title = 'Very Long Title That Might Be Used In Some Cases Where We Need More Descriptive Text';
        $description = 'This is a very long description that might be used when we need to provide detailed information about the option available to the user in the list message format.';
        $rowId = 'long_text_option';

        $listRow = new ListRow($title, $description, $rowId);
        $data = $listRow->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertEquals($description, $data['description']);
        $this->assertEquals($rowId, $data['rowId']);
    }

    /** @test */
    public function it_can_create_list_row_with_special_characters()
    {
        $title = 'Opção com Acentos & Símbolos!';
        $description = 'Descrição com caracteres especiais: @#$%^&*()';
        $rowId = 'special_chars_123';

        $listRow = new ListRow($title, $description, $rowId);
        $data = $listRow->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertEquals($description, $data['description']);
        $this->assertEquals($rowId, $data['rowId']);
    }

    /** @test */
    public function it_can_create_list_row_with_numeric_strings()
    {
        $title = '123';
        $description = '456.789';
        $rowId = '999';

        $listRow = new ListRow($title, $description, $rowId);
        $data = $listRow->toArray();

        $this->assertEquals('123', $data['title']);
        $this->assertEquals('456.789', $data['description']);
        $this->assertEquals('999', $data['rowId']);
    }

    /** @test */
    public function it_returns_correct_array_structure()
    {
        $listRow = new ListRow('Test', 'Test Description', 'test_id');
        $data = $listRow->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('rowId', $data);
        $this->assertCount(3, $data);
    }
}