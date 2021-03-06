<?php


namespace App\Repositories;


use App\Models\Contact;
use App\Models\Share;
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

    public function getContactSharesByReceivers(int $contact_id): Collection
    {
        return User::query()
            ->leftJoin('shares', 'users.id', '=', 'shares.user_id')
            ->leftJoin('contacts', 'shares.contact_id', '=', 'contacts.id')
            ->where('shares.contact_id', '=', $contact_id)
            ->orderBy('users.name')
            ->select(
                'shares.id as share_id',
                'shares.user_id as receiver_id',
                'users.name as receiver_name',
            )
            ->get();
    }

    public function getUserAllContacts(User $user): Collection
    {
        return Contact::query()
            ->leftJoin('users', 'contacts.creator_id', '=', 'users.id')
            ->Where('contacts.creator_id', '=', $user->id)
            ->orWhereIn('contacts.id',
                Contact::query()
                    ->leftJoin('shares','contacts.id', '=', 'shares.contact_id')
                    ->where('shares.user_id', '=', $user->id)
                    ->select('contacts.id as id')
                    ->get()
            )
            ->select(
                'contacts.id as id',
                'contacts.name as name',
                'phone',
                'creator_id',
                'users.name as creator_name'
            )
            ->orderBy('contacts.name')
            ->get();
    }

    public function updateModel(Request $request,Object $contact): void
    {
        $contact->update($request->validated());
    }


}
