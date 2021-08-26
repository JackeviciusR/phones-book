<?php


namespace App\Managers;


use Illuminate\Database\Eloquent\Model;

Abstract class AbstractManager
{
    public function getContactById(int $contact_id): Model
    {
        return $this->repository->getContactById($contact_id);
    }

    public function getReceiverById(int $user_id): Model
    {
        return $this->repository->getReceiverById($user_id);
    }
}
