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
    public function recursiveOnlyForCollection(Collection $collection, array $only): Collection
    {
        $result = [];

        foreach ($only as $name => $nest) {
            $name = is_string($nest) ? $nest : $name;

            if ($name === '*') {
                foreach ($collection as $key => $value) {
                    $result[$key] = $this->getValue($value, $nest);
                }
            } else {
                $result[$name] = $this->getValue($collection->get($name), $nest);
            }
        }

        return new Collection($result);
    }

    /**
     * @param  array $array
     * @param  array $only
     * @return array
     */
    public function recursiveOnlyForArr(array $array, array $only): array
    {
        $result = [];

        foreach ($only as $name => $nest) {
            $name = is_string($nest) ? $nest : $name;

            if ($name === '*') {
                foreach ($array as $key => $value) {
                    $result[$key] = $this->getValue($value, $nest);
                }
            } else {
                $result[$name] = $this->getValue($array[$name], $nest);
            }
        }

        return $result;
    }

    /**
     * @param  Model $model
     * @param  array $only
     * @return array
     */
    public function RecursiveOnly(Model $model, array $only): array
    {
        $result = [];

        foreach ($only as $name => $nest) {
            $name = is_string($nest) ? $nest : $name;

            if ($name === '*') {
                foreach ($model->attributesToArray() as $key => $value) {
                    $result[$key] = $this->getValue($value, $nest);
                }
            } else {
                $result[$name] = $this->getValue($model->$name, $nest);
            }
        }

        return $result;
    }

    /**
     * @param  mixed $value
     * @param  string|array|Closure $only
     * @return mixed
     */
    private function getValue($value, $only)
    {
        if (is_array($only)) {
            if (is_array($value)) {
                return Arr::recursiveOnly($value, $only);
            } else {
                return $value->recursiveOnly($only);
            }
        } elseif (is_callable($only)) {
            return $only($value);
        } else {
            return $value;
        }
    }
}
