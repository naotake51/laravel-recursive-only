<?php

namespace Naotake51\RecursiveOnly\Contracts;

interface HasRecursiveOnly
{
    public function recursiveOnly(array $only, array $parents = []): array;
}
