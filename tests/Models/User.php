<?php

namespace Naotake51\RecursiveOnly\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Naotake51\RecursiveOnly\Contracts\HasRecursiveOnly;
use Naotake51\RecursiveOnly\Traits\RecursiveOnly;

class User extends Model implements HasRecursiveOnly
{
    use RecursiveOnly;

    protected $fillable = [
        'name',
        'age',
    ];
}
