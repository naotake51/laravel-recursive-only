<?php

namespace Naotake51\RecursiveOnly\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Naotake51\RecursiveOnly\Contracts\HasRecursiveOnly;
use Naotake51\RecursiveOnly\Traits\RecursiveOnly;

class Post extends Model implements HasRecursiveOnly
{
    use RecursiveOnly;

    protected $fillable = [
        'title',
        'body',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
