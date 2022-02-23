<?php

namespace App\Repo;

use App\Repo\Contracts\RepositoryInterface;

class BaseRepo implements RepositoryInterface
{
    /**@var \Illuminate\Database\Eloquent\Model $eloquent */
    protected $eloquent;

    /**@var \Illuminate\Database\Eloquent\Builder $builder */
    protected $builder;

    function find($id, $columns = ['*'])
    {
        return $this->eloquent = $this->builder->find($id, $columns);
    }

    function findOrFail($id, $columns = ['*'])
    {
        return $this->eloquent = $this->builder->findOrFail($id, $columns);
    }

    function save(array $data)
    {
        foreach ($data as $key => $value) {
            $this->eloquent->$key = $value;
        }
        if (!$this->eloquent->exists) {
            $this->eloquent->user_id = auth()->id();
        }
        $this->eloquent->save();
        return $this->eloquent;
    }

    function softDelete()
    {
        $this->eloquent->delete();
    }

    function delete()
    {
        $this->eloquent->forceDelete();
    }

    function log($model)
    {

    }

    function getAll($columns = ['*'])
    {
        return $this->builder->get($columns);
    }
}
