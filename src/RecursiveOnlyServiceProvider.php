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

        Collection::macro('recursiveOnly', function (array $only) use ($recursiveOnly) {
            /** @var Collection $this */
            return $recursiveOnly->recursiveOnlyForCollection($this, $only);
        });

        Arr::macro('recursiveOnly', function (array $array, array $only) use ($recursiveOnly) {
            return $recursiveOnly->recursiveOnlyForArr($array, $only);
        });
    }
}
