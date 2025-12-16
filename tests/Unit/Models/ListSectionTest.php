<?php
// tests/Unit/Models/ListSectionTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Models\ListRow;
use SamuelTerra22\LaravelEvolutionClient\Models\ListSection;

class ListSectionTest extends TestCase
{
    /** @test */
    public function it_can_create_list_section_with_list_row_objects()
    {
        $title = 'Section 1';
        $rows = [
            new ListRow('Option 1', 'Description 1', 'opt1'),
            new ListRow('Option 2', 'Description 2', 'opt2'),
        ];

        $listSection = new ListSection($title, $rows);
        $data = $listSection->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertCount(2, $data['rows']);
        $this->assertEquals('Option 1', $data['rows'][0]['title']);
        $this->assertEquals('Description 1', $data['rows'][0]['description']);
        $this->assertEquals('opt1', $data['rows'][0]['rowId']);
        $this->assertEquals('Option 2', $data['rows'][1]['title']);
        $this->assertEquals('Description 2', $data['rows'][1]['description']);
        $this->assertEquals('opt2', $data['rows'][1]['rowId']);
    }

    /** @test */
    public function it_can_create_list_section_with_array_rows()
    {
        $title = 'Section 2';
        $rows = [
            [
                'title' => 'Option 3',
                'description' => 'Description 3',
                'rowId' => 'opt3'
            ],
            [
                'title' => 'Option 4',
                'description' => 'Description 4',
                'rowId' => 'opt4'
            ]
        ];

        $listSection = new ListSection($title, $rows);
        $data = $listSection->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertCount(2, $data['rows']);
        $this->assertEquals($rows[0], $data['rows'][0]);
        $this->assertEquals($rows[1], $data['rows'][1]);
    }

    /** @test */
    public function it_can_create_list_section_with_mixed_row_types()
    {
        $title = 'Mixed Section';
        $rows = [
            new ListRow('Object Row', 'Object Description', 'obj_row'),
            [
                'title' => 'Array Row',
                'description' => 'Array Description',
                'rowId' => 'arr_row'
            ]
        ];

        $listSection = new ListSection($title, $rows);
        $data = $listSection->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertCount(2, $data['rows']);
        $this->assertEquals('Object Row', $data['rows'][0]['title']);
        $this->assertEquals('Object Description', $data['rows'][0]['description']);
        $this->assertEquals('obj_row', $data['rows'][0]['rowId']);
        $this->assertEquals('Array Row', $data['rows'][1]['title']);
        $this->assertEquals('Array Description', $data['rows'][1]['description']);
        $this->assertEquals('arr_row', $data['rows'][1]['rowId']);
    }

    /** @test */
    public function it_can_create_list_section_with_empty_rows()
    {
        $title = 'Empty Section';
        $rows = [];

        $listSection = new ListSection($title, $rows);
        $data = $listSection->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertIsArray($data['rows']);
        $this->assertCount(0, $data['rows']);
    }

    /** @test */
    public function it_can_create_list_section_with_special_characters_in_title()
    {
        $title = 'Seção com Acentos & Símbolos!';
        $rows = [
            new ListRow('Opção 1', 'Descrição 1', 'opt1')
        ];

        $listSection = new ListSection($title, $rows);
        $data = $listSection->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertCount(1, $data['rows']);
    }

    /** @test */
    public function it_can_create_list_section_with_single_row()
    {
        $title = 'Single Row Section';
        $rows = [
            new ListRow('Only Option', 'Only Description', 'only_opt')
        ];

        $listSection = new ListSection($title, $rows);
        $data = $listSection->toArray();

        $this->assertEquals($title, $data['title']);
        $this->assertCount(1, $data['rows']);
        $this->assertEquals('Only Option', $data['rows'][0]['title']);
    }

    /** @test */
    public function it_returns_correct_array_structure()
    {
        $listSection = new ListSection('Test Section', []);
        $data = $listSection->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('rows', $data);
        $this->assertCount(2, $data);
    }

    /** @test */
    public function it_processes_list_row_objects_correctly()
    {
        $row1 = new ListRow('Row 1', 'Desc 1', 'r1');
        $row2 = new ListRow('Row 2', 'Desc 2', 'r2');
        
        $listSection = new ListSection('Test', [$row1, $row2]);
        $data = $listSection->toArray();

        // Verify that ListRow objects were converted to arrays
        $this->assertIsArray($data['rows'][0]);
        $this->assertIsArray($data['rows'][1]);
        $this->assertArrayHasKey('title', $data['rows'][0]);
        $this->assertArrayHasKey('description', $data['rows'][0]);
        $this->assertArrayHasKey('rowId', $data['rows'][0]);
    }
}