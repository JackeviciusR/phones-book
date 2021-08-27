<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    Use RefreshDatabase;

    /** @test */
    public function testCanContactsIndexPageRenderedProperly()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/contacts');

        $response->assertStatus(200);
    }

    /** @test */
    public function testCanContactsCreatePageRenderedProperly()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/contacts/create');

        $response->assertStatus(200);
    }

    /** @test */
    public function testCanCreateContact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/contacts', [
            'name'=>'testing contact name',
            'phone'=>'+37060133333',
            'creator_id'=>$user->id,
        ]);

        $response->assertStatus(302);

        $contact = Contact::first();

        $this->assertEquals(1, Contact::count());
        $this->assertIsObject($contact);
        $this->assertEquals('testing contact name', $contact->name);
        $this->assertEquals('+37060133333', $contact->phone);
        $this->assertEquals($user->id, $contact->creator_id);
        $this->assertInstanceOf(User::class, $contact->creator);
    }

    /** @test */
    public function testCanEditContact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = $user->contacts()->save(Contact::factory()->make());

        $response = $this->get("contacts/{$contact->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('contacts.edit')
            ->assertViewHasAll([
            'contact'=>$contact,
            'creator_id'=>$contact->creator_id,
            ]);

        $view = $this->view('contacts.edit', [
            'contact'=>$contact,
            'creator_id'=>$contact->creator_id,
        ]);
        $view->assertSee('creator_id');
    }

    /** @test  */
    public function testCanEditOtherUserContact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $secondUser = User::factory()->create();
        $secondUserContact = $secondUser->contacts()->save(Contact::factory()->make());

        $response = $this->get("contacts/{$secondUserContact->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect();
    }

    /** @test  */
    public function testCanShowOtherUserContact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $secondUser = User::factory()->create();
        $secondUserContact = $secondUser->contacts()->save(Contact::factory()->make());

        $response = $this->get("contacts/{$secondUserContact->id}");

        $response->assertStatus(302);
        $response->assertRedirect();
    }


    /** @test */
    public function testCanUpdateContact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = $user->contacts()->save(Contact::factory()->make());

        $response = $this->put("contacts/{$contact->id}", [
            'name'=>'updated name',
            'phone'=>'+37060599450',
            'creator_id'=>$contact->id,
        ]);

        $updatedContact = Contact::first();

        $response->assertStatus(302);
        $this->assertEquals('updated name', $updatedContact->name);
        $this->assertEquals('+37060599450', $updatedContact->phone);
        $response->assertRedirect('contacts');
    }

    /** @test */
    public function testCanDeleteContact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = $user->contacts()->save(Contact::factory()->make());

        $response = $this->delete("contacts/{$contact->id}");

        $response->assertStatus(302);
        $this->assertEquals(0, Contact::count());
    }

}
