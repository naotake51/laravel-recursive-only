<?php

namespace Naotake51\RecursiveOnly\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Naotake51\RecursiveOnly\RecursiveOnlyServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Naotake51\RecursiveOnly\Tests\Models\Comment;
use Naotake51\RecursiveOnly\Tests\Models\Post;
use Naotake51\RecursiveOnly\Tests\Models\User;

class RecursiveOnlyForModelTest extends TestCase
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
    public function testRecursiveOnly(Model $model, array $only, array $expected)
    {
        $actual = $model->recursiveOnly($only);
        $this->assertEquals($expected, $actual);
    }

    public function dataRecursiveOnly(): array
    {
        return [
            'only' => [
                'model' => (new Post)->fill([
                    'title' => 'aaaa',
                    'body' => 'bbbb',
                ]),
                'only' => [
                    'title',
                ],
                'expected' => [
                    'title' => 'aaaa',
                ],
            ],
            '* (any)' => [
                'model' => (new Post)->fill([
                    'title' => 'aaaa',
                    'body' => 'bbbb',
                ]),
                'only' => [
                    '*',
                ],
                'expected' => [
                    'title' => 'aaaa',
                    'body' => 'bbbb',
                ],
            ],
            'callback' => [
                'model' => (new Post)->fill([
                    'title' => 'aaaa',
                    'body' => 'bbbb',
                ]),
                'only' => [
                    'title' => function ($value) {
                        return $value . 'xxxx';
                    },
                ],
                'expected' => [
                    'title' => 'aaaaxxxx',
                ],
            ],
            'nest only' => [
                'model' => (new Post)->fill([
                    'title' => 'aaaa',
                    'body' => 'bbbb'
                ])->setRelations([
                    'author' => (new User)->fill([
                        'name' => 'taro',
                        'age' => 10
                    ]),
                ]),
                'only' => [
                    'author' => [
                        'name'
                    ],
                ],
                'expected' => [
                    'author' => [
                        'name' => 'taro'
                    ],
                ],
            ],
            'nest * (any) collection' => [
                'model' => (new Post)->fill([
                    'title' => 'aaaa',
                    'body' => 'bbbb'
                ])->setRelations([
                    'comments' => new EloquentCollection([
                        (new Comment)->fill([
                            'body' => 'xxxx',
                            'order' => 1,
                        ]),
                        (new Comment)->fill([
                            'body' => 'yyyy',
                            'order' => 2,
                        ]),
                    ]),
                ]),
                'only' => [
                    'comments' => [
                        '*' => [
                            'body'
                        ]
                    ],
                ],
                'expected' => [
                    'comments' => new Collection([
                        [
                            'body' => 'xxxx'
                        ],
                        [
                            'body' => 'yyyy'
                        ],
                    ]),
                ],
            ],
        ];
    }
}
