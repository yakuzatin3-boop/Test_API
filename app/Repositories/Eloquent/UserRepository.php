<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends FilterableRepository implements UserRepositoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $filterable = ['role' => 'role', 'status' => 'status', 'is_active' => 'is_active'];

    /**
     * @var list<string>
     */
    protected array $searchable = ['name', 'email'];

    /**
     * @var list<string>
     */
    protected array $with = ['customer:id,user_id,name,email'];

    /**
     * @var list<string>
     */
    protected array $withCount = ['posts'];

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
