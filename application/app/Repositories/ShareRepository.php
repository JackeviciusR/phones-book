<?php


namespace App\Repositories;


use App\Models\Contact;
use App\Models\Share;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ShareRepository extends AbstractRepository
{
    public function createModel(Request $request)
    {
        Share::create($request->validated());

    }

    public function deleteModel(Model $model): bool
    {
        DB::beginTransaction();

        try {
            $model->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        return true;
    }

    public function getContactReceiversColumnValues(Contact $contact, string $columnName)
    {
        return ($contact->receivers()->get())
            ->map
            ->only([$columnName]);
    }

    public function getUsersWithoutContact(Contact $contact): Collection
    {
        $receiversIds = $this->getContactReceiversColumnValues($contact, 'id');

        return User::query()
            ->where('id', '!=', auth()->user()->id)
            ->whereNotIn('id', $receiversIds)
            ->orderBy('name')
            ->get();
    }

    public function getSharedData(int $user_id, string $orderRule): Collection
    {
        $orderRules = [
            'byContactName'=>'contact_name, receiver_name',
            'byReceiverName'=>'receiver_name, contact_name',
        ];

        return  DB::table('contacts')
            ->leftJoin('shares', 'contacts.id', '=', 'shares.contact_id')
            ->leftJoin('users', 'shares.user_id', '=', 'users.id')
            ->where('contacts.creator_id', '=', $user_id)
            ->whereNotNull('users.id')
            ->select(
                'contacts.id as contact_id',
                'contacts.name as contact_name',
                'contacts.phone as contact_phone',
                'creator_id',
                'users.id as receiver_id',
                'users.name as receiver_name',
                'shares.id as share_id',
            )
            ->orderByRaw($orderRules[$orderRule])
            ->get();
    }

}
