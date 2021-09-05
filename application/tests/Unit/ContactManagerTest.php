<?php

namespace Tests\Unit;

use App\Managers\ContactManager;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\ContactRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ContactManagerTest extends TestCase
{
    use RefreshDatabase;

    public function testCanLoadGetContactByIdWithContactManager()
    {
        $user = User::factory()->create();
        $contact = $user->contacts()->save(Contact::factory()->make());

        $mockRepository = $this->mock(ContactRepository::class);
        $mockRepository->shouldReceive('getContactById')
            ->with($contact->id)
            ->once()
            ->andReturn($contact);

        $manager = new ContactManager($mockRepository);
        $returnedContact = $manager->getContactById($contact->id);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals($contact->id, $returnedContact->id);
        $this->assertEquals($contact, $returnedContact);
    }

    public function testCanLoadGetReceiverByIdWithContactManager()
    {
        $user = User::factory()->create();
        $contact = $user->contacts()->save(Contact::factory()->make());
        $receiver = $contact->receivers()->save(User::factory()->make());

        $mockRepository = $this->mock(ContactRepository::class);
        $mockRepository->shouldReceive('getReceiverById')
            ->with($receiver->id)
            ->once()
            ->andReturn($receiver);

        $manager = new ContactManager($mockRepository);
        $returnedReceiver = $manager->getReceiverById($receiver->id);

        $this->assertInstanceOf(User::class, $returnedReceiver);
        $this->assertEquals($receiver->id, $returnedReceiver->id);
        $this->assertEquals($receiver, $returnedReceiver);
    }

}
