<?php
// tests/Unit/Models/ContactTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Models\Contact;

class ContactTest extends TestCase
{
    /** @test */
    public function it_can_create_contact_with_required_fields_only()
    {
        $fullName = 'John Doe';
        $wuid = '5511999999999';
        $phoneNumber = '5511999999999';

        $contact = new Contact($fullName, $wuid, $phoneNumber);
        $data = $contact->toArray();

        $this->assertEquals($fullName, $data['fullName']);
        $this->assertEquals($wuid, $data['wuid']);
        $this->assertEquals($phoneNumber, $data['phoneNumber']);
        $this->assertCount(3, $data);
        $this->assertArrayNotHasKey('organization', $data);
        $this->assertArrayNotHasKey('email', $data);
        $this->assertArrayNotHasKey('url', $data);
    }

    /** @test */
    public function it_can_create_contact_with_all_fields()
    {
        $fullName = 'Jane Smith';
        $wuid = '5511888888888';
        $phoneNumber = '5511888888888';
        $organization = 'ACME Corp';
        $email = 'jane@acme.com';
        $url = 'https://acme.com';

        $contact = new Contact($fullName, $wuid, $phoneNumber, $organization, $email, $url);
        $data = $contact->toArray();

        $this->assertEquals($fullName, $data['fullName']);
        $this->assertEquals($wuid, $data['wuid']);
        $this->assertEquals($phoneNumber, $data['phoneNumber']);
        $this->assertEquals($organization, $data['organization']);
        $this->assertEquals($email, $data['email']);
        $this->assertEquals($url, $data['url']);
        $this->assertCount(6, $data);
    }

    /** @test */
    public function it_can_create_contact_with_organization_only()
    {
        $fullName = 'Bob Johnson';
        $wuid = '5511777777777';
        $phoneNumber = '5511777777777';
        $organization = 'Tech Company';

        $contact = new Contact($fullName, $wuid, $phoneNumber, $organization);
        $data = $contact->toArray();

        $this->assertEquals($fullName, $data['fullName']);
        $this->assertEquals($wuid, $data['wuid']);
        $this->assertEquals($phoneNumber, $data['phoneNumber']);
        $this->assertEquals($organization, $data['organization']);
        $this->assertCount(4, $data);
        $this->assertArrayNotHasKey('email', $data);
        $this->assertArrayNotHasKey('url', $data);
    }

    /** @test */
    public function it_can_create_contact_with_email_only()
    {
        $fullName = 'Alice Brown';
        $wuid = '5511666666666';
        $phoneNumber = '5511666666666';
        $email = 'alice@example.com';

        $contact = new Contact($fullName, $wuid, $phoneNumber, null, $email);
        $data = $contact->toArray();

        $this->assertEquals($fullName, $data['fullName']);
        $this->assertEquals($wuid, $data['wuid']);
        $this->assertEquals($phoneNumber, $data['phoneNumber']);
        $this->assertEquals($email, $data['email']);
        $this->assertCount(4, $data);
        $this->assertArrayNotHasKey('organization', $data);
        $this->assertArrayNotHasKey('url', $data);
    }

    /** @test */
    public function it_can_create_contact_with_url_only()
    {
        $fullName = 'Charlie Wilson';
        $wuid = '5511555555555';
        $phoneNumber = '5511555555555';
        $url = 'https://charliewilson.com';

        $contact = new Contact($fullName, $wuid, $phoneNumber, null, null, $url);
        $data = $contact->toArray();

        $this->assertEquals($fullName, $data['fullName']);
        $this->assertEquals($wuid, $data['wuid']);
        $this->assertEquals($phoneNumber, $data['phoneNumber']);
        $this->assertEquals($url, $data['url']);
        $this->assertCount(4, $data);
        $this->assertArrayNotHasKey('organization', $data);
        $this->assertArrayNotHasKey('email', $data);
    }

    /** @test */
    public function it_can_create_contact_with_special_characters()
    {
        $fullName = 'José María García';
        $wuid = '5511444444444';
        $phoneNumber = '+55 (11) 4444-4444';
        $organization = 'Empresa & Cia.';
        $email = 'josé@empresa.com.br';
        $url = 'https://empresa.com.br/perfil?user=josé';

        $contact = new Contact($fullName, $wuid, $phoneNumber, $organization, $email, $url);
        $data = $contact->toArray();

        $this->assertEquals($fullName, $data['fullName']);
        $this->assertEquals($wuid, $data['wuid']);
        $this->assertEquals($phoneNumber, $data['phoneNumber']);
        $this->assertEquals($organization, $data['organization']);
        $this->assertEquals($email, $data['email']);
        $this->assertEquals($url, $data['url']);
    }

    /** @test */
    public function it_excludes_null_optional_fields_from_array()
    {
        $contact = new Contact('Test User', '123456', '123456', null, null, null);
        $data = $contact->toArray();

        $this->assertArrayHasKey('fullName', $data);
        $this->assertArrayHasKey('wuid', $data);
        $this->assertArrayHasKey('phoneNumber', $data);
        $this->assertArrayNotHasKey('organization', $data);
        $this->assertArrayNotHasKey('email', $data);
        $this->assertArrayNotHasKey('url', $data);
        $this->assertCount(3, $data);
    }

    /** @test */
    public function it_includes_empty_string_optional_fields_in_array()
    {
        $contact = new Contact('Test User', '123456', '123456', '', '', '');
        $data = $contact->toArray();

        $this->assertArrayHasKey('organization', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('url', $data);
        $this->assertEquals('', $data['organization']);
        $this->assertEquals('', $data['email']);
        $this->assertEquals('', $data['url']);
        $this->assertCount(6, $data);
    }

    /** @test */
    public function it_can_create_contact_with_long_values()
    {
        $fullName = 'Very Long Full Name That Might Be Used In Some Real World Scenarios Where People Have Long Names';
        $wuid = '5511333333333';
        $phoneNumber = '5511333333333';
        $organization = 'Very Long Organization Name That Spans Multiple Words And Might Include Special Characters & Symbols';
        $email = 'very.long.email.address@very.long.domain.name.example.com';
        $url = 'https://very.long.url.example.com/path/to/resource?with=parameters&and=values';

        $contact = new Contact($fullName, $wuid, $phoneNumber, $organization, $email, $url);
        $data = $contact->toArray();

        $this->assertEquals($fullName, $data['fullName']);
        $this->assertEquals($organization, $data['organization']);
        $this->assertEquals($email, $data['email']);
        $this->assertEquals($url, $data['url']);
    }
}