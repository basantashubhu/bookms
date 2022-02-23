<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Book extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $appends = ['book_url'];

    public function bookUrl() : Attribute {
        return Attribute::get(
            function ($value, $attributes) {
                return $attributes['filename'] ? route('book.url', ['file' => Crypt::encryptString($attributes['id'])]) : null;
            }
        );
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
