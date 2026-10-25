<?php

namespace App\Services\Access;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;

class UserService extends CrudService
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['customer:id,user_id,name,email', 'posts:id,user_id,title,slug,status,published_at'])->loadCount(['posts']);
    }

    public function update(Model $model, array $attributes): Model
    {
        if (array_key_exists('password', $attributes) && blank($attributes['password'])) {
            unset($attributes['password']);
        }

        return parent::update($model, $attributes);
    }
}
