<?php


namespace App\Managers;


use App\Models\Contact;
use App\Models\Share;
use App\Repositories\ShareRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class ShareManager extends AbstractManager
{
    public function __construct(protected ShareRepository $repository)
    {
    }

    public function createModel(Request $request): void
    {
        $this->repository->createModel($request);
    }

    public function getUsersWithoutContact(Contact $contact): Collection
    {
        return $this->repository->getUsersWithoutContact($contact);
    }

    public function deleteModel(Model $model): bool
    {
        return $this->repository->deleteModel($model);
    }

    public function getSharedData(int $user_id, string $orderRule): Collection
    {
        return $this->repository->getSharedData($user_id, $orderRule);
    }


}
