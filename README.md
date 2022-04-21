# laravel-recursive-only

Make Laravel's `only` a little more useful.

- recursive
- `*`(any keys)
- callback

## Requirements

TODO

## Install

```
composer require naotake51/laravel-recursive-only
```

## Motivation

I found deeply nested Laravel's `Resource` to be a pain to define.

I want to write `Resource` more simply.

## Using

your model add trait `RecursiveOnly`

```php
use Naotake51\RecursiveOnly\Contracts\HasRecursiveOnly;
use Naotake51\RecursiveOnly\Traits\RecursiveOnly;

class Post extends Model implements HasRecursiveOnly
{
    use RecursiveOnly;

    // ...
}
```

call `recursiveOnly`.

- `*` is mean any keys.
- The value can be changed in the callback.

```php
$post = Post::with(['author', 'comments'])->find(1);

$data = $post->recursiveOnly([
    'author' => [
        'name'
    ],
    'comments' => [
        '*' => [
            'title' => fn ($value /**, ...parents */) => "# $value", // use callback
            'body',
        ]
    ]
]);

// [
//     'author' => [
//         'name' => '...'
//     ],
//     'comments' => Illuminate\Support\Collection([
//         [
//             'title' => '# ...',
//             'body' => '...',
//         ],
//         ...
//     ])
// ]
```

```php
$posts = Post::with(['author', 'comments'])->get();

$data = $posts->recursiveOnly([
    '*' => [
        'author' => [
            'name'
        ],
        'comments' => [
            '*' => [
                'body'
            ]
        ]
    ]
]);

// Illuminate\Support\Collection([
//     [
//         'author' => [
//             'name' => '...'
//         ],
//         'comments' => Illuminate\Support\Collection([
//             [
//                 'body' => '...'
//             ],
//             ...
//         ])
//     ],
//     ...
// ])
```

For arrays, use `Arr::recursiveOnly`

```php
$posts = Post::with(['author', 'comments'])->get()->toArray();

$data = Arr::recursiveOnly($posts, [
    '*' => [
        'author' => [
            'name'
        ],
        'comments' => [
            '*' => [
                'body'
            ]
        ]
    ]
]);

// [
//     [
//         'author' => [
//             'name' => '...'
//         ],
//         'comments' => [
//             [
//                 'body' => '...'
//             ],
//             ...
//         ]
//     ],
//     ...
// ]
```
