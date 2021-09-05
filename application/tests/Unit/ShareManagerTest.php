<?php

namespace Tests\Unit;

use App\Managers\ShareManager;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\ShareRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShareManagerTest extends TestCase
{
    use RefreshDatabase;

    public function testCanLoadGetContactByIdWithShareManager()
    {
        $user = User::factory()->create();
        $contact = $user->contacts()->save(Contact::factory()->make());

        $mockRepository = $this->mock(ShareRepository::class);
        $mockRepository->shouldReceive('getContactById')
            ->with($contact->id)
            ->once()
            ->andReturn($contact);

        $manager = new ShareManager($mockRepository);
        $returnedContact = $manager->getContactById($contact->id);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals($contact->id, $returnedContact->id);
        $this->assertEquals($contact, $returnedContact);
    }

    public function testCanLoadGetReceiverByIdWithShareManager()
    {
        $user = User::factory()->create();
        $contact = $user->contacts()->save(Contact::factory()->make());
        $receiver = $contact->receivers()->save(User::factory()->make());

        $mockRepository = $this->mock(ShareRepository::class);
        $mockRepository->shouldReceive('getReceiverById')
            ->with($receiver->id)
            ->once()
            ->andReturn($receiver);

        $manager = new ShareManager($mockRepository);
        $returnedReceiver = $manager->getReceiverById($receiver->id);

        $this->assertInstanceOf(User::class, $returnedReceiver);
        $this->assertEquals($receiver->id, $returnedReceiver->id);
        $this->assertEquals($receiver, $returnedReceiver);
    }

}
