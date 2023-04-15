<?php

namespace Naotake51\RecursiveOnly;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Closure;

class RecursiveOnly
{
    /**
     * @param  Collection $collection
     * @param  array $only
     * @return Collection
     */
    public function recursiveOnlyForCollection(Collection $collection, array $only, array $parents = []): Collection
    {
        $parents[] = $collection;
        $result = [];

        foreach ($only as $name => $nest) {
            $name = is_string($nest) ? $nest : $name;

            if ($name === '*') {
                foreach ($collection as $key => $value) {
                    $result[$key] = $this->getValue($value, $nest, $parents);
                }
            } else {
                $result[$name] = $this->getValue($collection->get($name), $nest, $parents);
            }
        }

        return new Collection($result);
    }

    /**
     * @param  array $array
     * @param  array $only
     * @return array
     */
    public function recursiveOnlyForArr(array $array, array $only, array $parents = []): array
    {
        $parents[] = $array;
        $result = [];

        foreach ($only as $name => $nest) {
            $name = is_string($nest) ? $nest : $name;

            if ($name === '*') {
                foreach ($array as $key => $value) {
                    $result[$key] = $this->getValue($value, $nest, $parents);
                }
            } else {
                $result[$name] = $this->getValue($array[$name], $nest, $parents);
            }
        }

        return $result;
    }

    /**
     * @param  Model $model
     * @param  array $only
     * @return array
     */
    public function recursiveOnlyForModel(Model $model, array $only, array $parents = []): array
    {
        $parents[] = $model;
        $result = [];

        foreach ($only as $name => $nest) {
            $name = is_string($nest) ? $nest : $name;

            if ($name === '*') {
                foreach ($model->attributesToArray() as $key => $value) {
                    $result[$key] = $this->getValue($value, $nest, $parents);
                }
            } else {
                $result[$name] = $this->getValue($model->$name, $nest, $parents);
            }
        }

        return $result;
    }

    /**
     * @param  mixed $value
     * @param  string|array|Closure $only
     * @return mixed
     */
    private function getValue($value, $only, $parents)
    {
        if (is_array($only)) {
            if (is_array($value)) {
                return Arr::recursiveOnly($value, $only, $parents);
            } else {
                return $value->recursiveOnly($only, $parents);
            }
        } elseif (is_callable($only)) {
            return $only($value, ...array_reverse($parents));
        } else {
            return $value;
        }
    }
}
