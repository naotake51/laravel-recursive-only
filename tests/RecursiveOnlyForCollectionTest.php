<?php

namespace Naotake51\RecursiveOnly\Tests;

use Orchestra\Testbench\TestCase;
use Naotake51\RecursiveOnly\RecursiveOnlyServiceProvider;
use Illuminate\Support\Collection;

class RecursiveOnlyForCollectionTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            RecursiveOnlyServiceProvider::class,
        ];
    }

    /**
     * @return void
     * @dataProvider dataRecursiveOnly
     */
    public function testRecursiveOnly(Collection $collection, array $only, Collection $expected)
    {
        $actual = $collection->recursiveOnly($only);
        $this->assertEquals($expected, $actual);
    }

    public function dataRecursiveOnly()
    {
        return [
            'only' => [
                'collection' => collect([
                    'a' => 1,
                    'b' => 2,
                ]),
                'only' => [
                    'a',
                ],
                'expected' => collect([
                    'a' => 1,
                ])
            ],
            '* (any)' => [
                'collection' => collect([
                    'a' => 1,
                    'b' => 2,
                ]),
                'only' => [
                    '*',
                ],
                'expected' => collect([
                    'a' => 1,
                    'b' => 2,
                ])
            ],
            'callback' => [
                'collection' => collect([
                    'a' => 1,
                    'b' => 2,
                ]),
                'only' => [
                    'a' => function ($value) {
                        return $value + 10;
                    },
                ],
                'expected' => collect([
                    'a' => 11,
                ])
            ],
            'nest only' => [
                'collection' => collect([
                    'a' => collect([
                        'x' => 1,
                        'y' => 2,
                    ]),
                    'b' => 2,
                ]),
                'only' => [
                    'a' => [
                        'x',
                    ],
                ],
                'expected' => collect([
                    'a' => collect([
                        'x' => 1,
                    ]),
                ])
            ],
            'nest * (any)' => [
                'collection' => collect([
                    'a' => collect([
                        'x' => 1,
                        'y' => 2,
                    ]),
                    'b' => 2,
                ]),
                'only' => [
                    'a' => [
                        '*',
                    ],
                ],
                'expected' => collect([
                    'a' => collect([
                        'x' => 1,
                        'y' => 2,
                    ]),
                ])
            ],
            'nest callback' => [
                'collection' => collect([
                    'a' => collect([
                        'x' => 1,
                        'y' => 2,
                    ]),
                    'b' => 2,
                ]),
                'only' => [
                    'a' => [
                        'x' => function ($value) {
                            return $value + 10;
                        },
                    ],
                ],
                'expected' => collect([
                    'a' => collect([
                        'x' => 11,
                    ]),
                ])
            ],
        ];
    }
}
