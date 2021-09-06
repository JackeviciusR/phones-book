<?php

namespace App\Managers;


use App\Models\Contact;
use App\Models\User;
use App\Repositories\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class ContactManager extends AbstractManager
{
    public function __construct(protected ContactRepository $repository)
    {
    }

    public function createModel(Request $request): void
    {
        $this->repository->createModel($request);
    }

    public function deleteModel(Object $contact): bool
    {
        return $this->repository->deleteModel($contact);
    }

    public function getContactSharesByReceivers(int $contact_id): Collection
    {
        return $this->repository->getContactSharesByReceivers($contact_id);
    }

    public function getUserAllContacts(User $user): Collection
    {
        return $this->repository->getUserAllContacts($user);
    }

    public function updateModel(Request $request, Object $contact): void
    {
        $this->repository->updateModel($request, $contact);
    }



}
