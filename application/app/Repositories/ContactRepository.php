<?php


namespace App\Repositories;


use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContactRepository extends AbstractRepository
{
    public function createModel(Request $request): void
    {
        Contact::create($request->validated());
    }

    public function deleteModel(Object $contact): bool
    {
        DB::beginTransaction();

        try {
            $contact->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        return true;
    }

    public function getOrderedContactSharesByReceivers(Contact $contact, string $columnName): Collection
    {
        $shares = $contact->contactShares()->get();
        $receivers = $contact->receivers()
            ->orderBy($columnName)
            ->get();

        $orderedShares = collect();
        foreach ($receivers as $receiver) {
            foreach ($shares as $share) {
                if ($receiver->id == $share->user_id) {
                    $orderedShares->push($share);
                }
            }
        }
        return $orderedShares;
    }

    public function getUserAllContacts(User $user): Collection
    {
        $receivedContacts = $user->receivedContacts()->get();
        $createdContacts = $user->contacts()->get();

        foreach ($receivedContacts as $receivedContact) {
            $createdContacts->push($receivedContact);
        }

        return $createdContacts;
    }

    public function updateModel(Request $request,Object $contact): void
    {
        $contact->update($request->validated());
    }


}
