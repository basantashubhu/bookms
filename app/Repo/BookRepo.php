<?php

namespace App\Repo;

use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class BookRepo extends BaseRepo
{
    public function __construct()
    {
        $this->eloquent = new Book();
        $this->builder = Book::query();
    }

    public function getAllWithUser()
    {
        $this->builder->with('user:id,first_name,last_name');
        return $this->getAll();
    }

    public function uploadFile(UploadedFile $file): string
    {
        $filename = time() . uniqid() .'.'. $file->getClientOriginalExtension();
        File::ensureDirectoryExists(storage_path('uploads'));
        $file->move(storage_path('uploads'), $filename);
        return $filename;
    }
}
