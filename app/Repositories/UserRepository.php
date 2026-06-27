<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(private User $model) {}

    public function findByEmail(string $email): ?User
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }

    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function create(array $data): User
    {
        return $this->model->newQuery()->create($data);
    }
}
