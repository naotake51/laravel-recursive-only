<?php

namespace Naotake51\RecursiveOnly\Tests;

use Orchestra\Testbench\TestCase;
use Naotake51\RecursiveOnly\RecursiveOnlyServiceProvider;
use Illuminate\Support\Arr;

class RecursiveOnlyForArrTest extends TestCase
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
    public function testRecursiveOnly(array $array, array $only, array $expected): void
    {
        $actual = Arr::recursiveOnly($array, $only);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function dataRecursiveOnly(): array
    {
        return [
            'only' => [
                'array' => [
                    'a' => 1,
                    'b' => 2,
                ],
                'only' => [
                    'a',
                ],
                'expected' => [
                    'a' => 1,
                ]
            ],
            '* (any)' => [
                'array' => [
                    'a' => 1,
                    'b' => 2,
                ],
                'only' => [
                    '*',
                ],
                'expected' => [
                    'a' => 1,
                    'b' => 2,
                ]
            ],
            'callback' => [
                'array' => [
                    'a' => 1,
                    'b' => 2,
                ],
                'only' => [
                    'a' => function ($value) {
                        return $value + 10;
                    },
                ],
                'expected' => [
                    'a' => 11,
                ]
            ],
            'nest only' => [
                'array' => [
                    'a' => [
                        'x' => 1,
                        'y' => 2,
                    ],
                    'b' => 2,
                ],
                'only' => [
                    'a' => [
                        'x',
                    ],
                ],
                'expected' => [
                    'a' => [
                        'x' => 1,
                    ],
                ]
            ],
            'nest * (any)' => [
                'array' => [
                    'a' => [
                        'x' => 1,
                        'y' => 2,
                    ],
                    'b' => 2,
                ],
                'only' => [
                    'a' => [
                        '*',
                    ],
                ],
                'expected' => [
                    'a' => [
                        'x' => 1,
                        'y' => 2,
                    ],
                ]
            ],
            'nest callback' => [
                'array' => [
                    'a' => [
                        'x' => 1,
                        'y' => 2,
                    ],
                    'b' => 2,
                ],
                'only' => [
                    'a' => [
                        'x' => function ($value) {
                            return $value + 10;
                        },
                    ],
                ],
                'expected' => [
                    'a' => [
                        'x' => 11,
                    ],
                ]
            ],
        ];
    }

    /**
     * @return void
     */
    public function testRecursiveOnlyCallbackParentsChain(): void
    {
        $array = [
            'grand' => $grand = [
                'parent' => $parent = [
                    'current' => 1,
                ],
            ],
        ];

        Arr::recursiveOnly($array, [
            'grand' => [
                'parent' => [
                    'current' => function ($value, ...$expected) use ($grand, $parent, $array) {
                        $this->assertSame($expected, [$parent, $grand, $array]);
                        return 'dummy';
                    }
                ]
            ]
        ]);
    }
}
