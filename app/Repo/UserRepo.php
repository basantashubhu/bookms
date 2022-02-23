<?php

namespace App\Repo;

use App\Models\User;

class UserRepo extends BaseRepo
{
    public function __construct()
    {
        $this->eloquent = new User();
        $this->builder = User::query();
    }

    public function findByEmail(string $email) {
        return $this->eloquent = $this->builder->where('email', $email)->first();
    }

    public function save(array $data)
    {
        foreach ($data as $key => $value) {
            $this->eloquent->$key = $value;
        }
        $this->eloquent->save();
        return $this->eloquent;
    }
}
