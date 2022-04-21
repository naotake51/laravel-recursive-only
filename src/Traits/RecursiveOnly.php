<?php

namespace Naotake51\RecursiveOnly\Traits;

use Naotake51\RecursiveOnly\RecursiveOnly as RecursiveOnlyImpl;

trait RecursiveOnly
{
    public function recursiveOnly(array $only, array $parents = []): array
    {
        return (new RecursiveOnlyImpl())->RecursiveOnly($this, $only, $parents);
    }
}
