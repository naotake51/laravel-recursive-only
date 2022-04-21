<?php

namespace Naotake51\RecursiveOnly;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class RecursiveOnlyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $recursiveOnly = new RecursiveOnly();

        Collection::macro('recursiveOnly', function (array $only, array $parents = []) use ($recursiveOnly) {
            /** @var Collection $this */
            return $recursiveOnly->recursiveOnlyForCollection($this, $only, $parents);
        });

        Arr::macro('recursiveOnly', function (array $array, array $only, array $parents = []) use ($recursiveOnly) {
            return $recursiveOnly->recursiveOnlyForArr($array, $only, $parents);
        });
    }
}
