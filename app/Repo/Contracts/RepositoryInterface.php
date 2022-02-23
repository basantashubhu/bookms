<?php
namespace App\Repo\Contracts;

interface RepositoryInterface
{
    public function getAll();
    public function find($id);
    public function save(array $data);
    public function softDelete();
    public function delete();
    public function log($model);
}
