<?php


namespace App\Repositories;


use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

Abstract class AbstractRepository
{
    public function getContactById(int $contact_id): Model
    {
        return Contact::query()
            ->where('id' ,'=', $contact_id)
            ->first();
    }

    public function getReceiverById(int $user_id): Model
    {
        return User::query()
            ->where('id' ,'=', $user_id)
            ->first();
    }

}
