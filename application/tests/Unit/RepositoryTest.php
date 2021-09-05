<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\Share;
use App\Models\User;
use App\Repositories\ShareRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetContactById()
    {
        $user = User::factory()->create();

        $contacts = Contact::factory()
            ->count(2)
            ->state(new Sequence(
                [
                    'name'=>'Petras',
                    'phone'=>'+37060533233',
                    'creator_id'=>$user->id,
                ],[
                    'name'=>'Jonas',
                    'phone'=>'+37060533234',
                    'creator_id'=>$user->id,
                ]))
            ->create();

        $this->assertEquals(1, User::count());
        $this->assertEquals(2, Contact::count());

        $contactRepository = new ShareRepository();
        $contact = $contactRepository->getContactById(2);

        $this->assertNotEquals(($contacts->first())->id, $contact->id);
        $this->assertEquals(($contacts->last())->id, $contact->id);
        $this->assertEquals(($contacts->last())->name, $contact->name);
        $this->assertEquals(($contacts->last())->phone, $contact->phone);
        $this->assertInstanceOf(Contact::class, $contact);
    }

    public function testCanGetReceiverById()
    {
        $user = User::factory()->create();

        $receiversNames = [
            ['name'=>'Kazys', 'email'=>'kazys@test.com'],
            ['name'=>'Petras', 'email'=>'petrass@test.com'],
        ];

        $contact = $user->contacts()->save(Contact::factory()->make());

        foreach ($receiversNames as $receiversName) {
            $contact->receivers()->save(User::factory()->make(
                $receiversName
            ));
        }

        $this->assertEquals(1, Contact::count() );
        $this->assertEquals(count($receiversNames), $contact->receivers()->count());
        $this->assertEquals(count($receiversNames)+1, User::count() );

        $repository = new ShareRepository();
        $receiver = $repository->getReceiverById((Share::first())->user_id);

        $this->assertEquals($receiversNames[0]['name'], $receiver->name );
        $this->assertEquals($receiversNames[0]['email'], $receiver->email );
        $this->assertInstanceOf(User::class, $receiver);
    }
}
