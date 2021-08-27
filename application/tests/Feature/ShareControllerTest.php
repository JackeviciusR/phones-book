<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Share;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShareControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSharesIndexPageRenderedProperly()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/shares');

        $response->assertStatus(200);
    }

    public function testCanSharesCreatePageRenderedProperly()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = $user->contacts()->save(Contact::factory()->make());

        $response = $this->get("/shares/{$contact->id}/create");

        $response->assertStatus(200);
    }

    /** @test */
    public function testCanCreateShare()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = $user->contacts()->save(Contact::factory()->make());

        $secondUser = User::factory()->create();

        $response = $this->post('/shares', [
            'contact_id'=>$contact->id,
            'user_id'=>$secondUser->id,
        ]);

        $response->assertStatus(302);

        $share = Share::first();

        $this->assertEquals(1, Share::count());
        $this->assertIsObject($contact);
        $this->assertEquals($contact->id, $share->contact_id);
        $this->assertEquals($secondUser->id, $share->user_id);
        $this->assertEquals($user->id, $contact->creator_id);
        $this->assertInstanceOf(User::class, $share->sharedWith);
        $this->assertInstanceOf(Contact::class, $share->sharedContact);
        $response->assertRedirect('shares');

    }

    /** @test */
    public function testCanDeleteShare()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = $user->contacts()->save(Contact::factory()->make());
        $contact->receivers()->save(User::factory()->make());

        $share = Share::first();

        $this->assertEquals(1, Share::count());

        $response = $this->delete("shares/{$share->id}");

        $response->assertStatus(302);
        $this->assertEquals(0, Share::count());
        $this->assertEquals(0, $contact->receivers->count());
        $this->assertEquals(0, $contact->contactShares->count());
        $response->assertRedirect();
    }

    /** @test */
    public function testCanDeleteSharesInCascadeDeletedContact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = $user->contacts()->save(Contact::factory()->make());
        $this->assertEquals(1, Contact::count());

        User::factory(2)->create();

        Share::factory()->create(['contact_id'=> $contact->id,
                'user_id'=>2,]);
        Share::factory()->create(['contact_id'=> $contact->id,
                'user_id'=>3,]);

        $this->assertEquals(2, Share::count());

        $response = $this->delete("contacts/{$contact->id}");

        $response->assertStatus(302);
        $this->assertEquals(0, Share::count());
        $this->assertEquals(0, Contact::count());
    }

}
