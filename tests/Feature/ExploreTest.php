<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use Illuminate\Http\Client\Request;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\PromiseInterface;

beforeEach(function () {
    actingAs(
        User::factory()
            ->hasVaults(Vault::factory())
            ->create()
    );

    Livewire::withoutLazyLoading();
});

it('can save new movie', function () {
    Http::fake([
        'https://api.themoviedb.org/3/search/multi*' => Http::response([
            'results' => [
                [
                    "backdrop_path" => "/wjxyKpUAZu6OVbKx9krhgI9KMl2.jpg",
                    "id" => 11528,
                    "title" => "The Sandlot",
                    "original_title" => "The Sandlot",
                    "overview" => "During a summer of friendship and adventure, one boy becomes a part of the gang, nine boys become a team and their leader becomes a legend by confronting the team",
                    "poster_path" => "/7PYqz0viEuW8qTvuGinUMjDWMnj.jpg",
                    "media_type" => "movie",
                    "adult" => false,
                    "original_language" => "en",
                    "release_date" => "1993-04-07",
                ]
            ]
        ], 200),
        "https://api.themoviedb.org/3/movie/*" => function (Request $request): PromiseInterface {
            if (
                !$request->header('Authorization')[0] === 'Bearer test-key' &&
                !in_array('release_dates', $request->data())
            ) {
                return Http::response(['error' => 'Invalid API key or parameters'], 401);
            }

            return Http::response([
                "backdrop_path" => "/wjxyKpUAZu6OVbKx9krhgI9KMl2.jpg",
                "id" => 1234,
                "title" => "The Sandlot",
                "original_title" => "The Sandlot",
                "overview" => "During a summer of friendship and adventure, one boy becomes a part of the gang, nine boys become a team and their leader becomes a legend by confronting the team",
                "poster_path" => "/7PYqz0viEuW8qTvuGinUMjDWMnj.jpg",
                "media_type" => "movie",
                "adult" => false,
                "original_language" => "en",
                "release_date" => "1993-04-07",
                'release_dates' => [
                    'results' => [
                        [
                            'iso_3166_1' => 'US',
                            'release_dates' => [
                                [
                                    'certification' => 'PG',
                                ],
                            ],
                        ],
                    ],
                ],
                'genres' => [
                    [
                        'id' => 1,
                        'name' => 'Family',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Comedy',
                    ],
                ],
                'runtime' => 101,
                'credits' => [
                    'cast' => [
                        [
                            'name' => 'Actor',
                        ],
                    ],
                ],
                'external_ids' => [
                    'imdb_id' => '1234'
                ]
            ], 200);
        },
    ]);

    Http::withToken('test-key')
        ->get("https://api.themoviedb.org/3/movie/1234", [
            'append_to_response' => 'release_dates',
        ]);

    livewire('pages::explore.index')
        ->set('search', 'The Sandlot')
        ->call('save', [
            'backdrop_path' => '/xJHokMbljvjADYdit5fK5VQsXEG.jpg',
            'id' => 157336,
            'imdb_id' => '1234',
            'title' => 'Interstellar',
            'original_title' => 'Interstellar',
            'overview' => 'Interstellar',
            'poster_path' => '/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
            'media_type' => 'movie',
            'release_date' => '2014-11-05',
            'rating' => 'PG',
            'genres' => 'Drama,Space',
            'runtime' => 101,
            'actors' => 'Actor',
        ])
        ->assertNoRedirect()
        ->assertHasNoErrors();
});

it('can save new tv show', function () {
    Http::fake([
        'https://api.themoviedb.org/3/search/multi*' => Http::response([
            'results' => [
                [
                    "backdrop_path" => "/wjxyKpUAZu6OVbKx9krhgI9KMl2.jpg",
                    "id" => 11528,
                    "name" => "Psych",
                    "original_name" => "Psych",
                    "overview" => "Thanks to his police officer father's efforts, Shawn Spencer spent his childhood developing a keen eye for detail (and a lasting dislike of his dad). Years late",
                    "poster_path" => "/7PYqz0viEuW8qTvuGinUMjDWMnj.jpg",
                    "media_type" => "tv",
                    "adult" => false,
                    "original_language" => "en",
                    "first_air_date" => "2014-03-26",
                ]
            ]
        ], 200),
        "https://api.themoviedb.org/3/tv/*" => function (Request $request): PromiseInterface {
            if (
                !$request->header('Authorization')[0] === 'Bearer test-key' &&
                !in_array('content_ratings', $request->data())
            ) {
                return Http::response(['error' => 'Invalid API key or parameters'], 401);
            }

            return Http::response([
                "backdrop_path" => "/zHA6kd8INvqMfGR9vDrn1GATKxs.jpg",
                "id" => 1234,
                "name" => "Psych",
                "original_name" => "Psych",
                "overview" => "Thanks to his police officer father's efforts, Shawn Spencer spent his childhood developing a keen eye for detail (and a lasting dislike of his dad). Years late",
                "poster_path" => "/fDI15gTVbtW5Sbv5QenqecRxWKJ.jpg",
                "media_type" => "tv",
                "adult" => false,
                "original_language" => "en",
                "first_air_date" => "2014-03-26",
                'content_ratings' => [
                    'results' => [
                        [
                            'iso_3166_1' => 'US',
                            'rating' => 'TV-PG',
                        ],
                    ],
                ],
                'genres' => [
                    [
                        'id' => 1,
                        'name' => 'Family',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Comedy',
                    ],
                ],
                'seasons' => 8,
                'credits' => [
                    'cast' => [
                        [
                            'name' => 'Actor',
                        ],
                    ],
                ],
                'external_ids' => [
                    'imdb_id' => '1234'
                ]
            ], 200);
        }
    ]);

    Http::withToken('test-key')
        ->get("https://api.themoviedb.org/3/tv/1234", [
            'append_to_response' => 'content_ratings',
        ]);

    livewire('pages::explore.index')
        ->set('search', 'Psych')
        ->call('save', [
            'backdrop_path' => '/xJHokMbljvjADYdit5fK5VQsXEG.jpg',
            'id' => 1573367,
            'imdb_id' => '1234',
            'name' => 'Suits',
            'original_name' => 'Suits',
            'overview' => 'Suits',
            'poster_path' => '/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
            'media_type' => 'tv',
            'first_air_date' => '2010-07-10',
            'rating' => 'TV-PG',
            'genres' => 'Drama,Comedy',
            'seasons' => 8,
            'actors' => 'Actor',
        ])
        ->assertNoRedirect()
        ->assertHasNoErrors();
});

it('can show popup alert when record already exists in vault', function () {
    livewire('pages::explore.index')
        ->call('save', [
            'id' => 1234,
            'title' => 'Test Movie',
        ])
        ->assertHasNoErrors();
});

it('can pass in and set search term', function () {
    Http::fake([
        "https://api.themoviedb.org/3/search/multi*" => Http::response([
            'results' => [
                [
                    "backdrop_path" => "/wjxyKpUAZu6OVbKx9krhgI9KMl2.jpg",
                    "id" => 11528,
                    "title" => "Toy Story",
                    "original_title" => "Toy Story",
                    "overview" => "During a summer of friendship and adventure, one boy becomes a part of the gang, nine boys become a team and their leader becomes a legend by confronting the team",
                    "poster_path" => "/7PYqz0viEuW8qTvuGinUMjDWMnj.jpg",
                    "media_type" => "movie",
                    "adult" => false,
                    "original_language" => "en",
                    "release_date" => "1993-04-07",
                ]
            ]
        ], 200),
        "https://api.themoviedb.org/3/movie/*" => function (Request $request): PromiseInterface {
            if (
                !$request->header('Authorization')[0] === 'Bearer test-key' &&
                !in_array('release_dates', $request->data())
            ) {
                return Http::response(['error' => 'Invalid API key or parameters'], 401);
            }

            return Http::response([
                "backdrop_path" => "/wjxyKpUAZu6OVbKx9krhgI9KMl2.jpg",
                "id" => 1234,
                "title" => "Toy Story",
                "original_title" => "Toy Story",
                "overview" => "During a summer of friendship and adventure, one boy becomes a part of the gang, nine boys become a team and their leader becomes a legend by confronting the team",
                "poster_path" => "/7PYqz0viEuW8qTvuGinUMjDWMnj.jpg",
                "media_type" => "movie",
                "adult" => false,
                "original_language" => "en",
                "release_date" => "1993-04-07",
                'release_dates' => [
                    'results' => [
                        [
                            'iso_3166_1' => 'US',
                            'release_dates' => [
                                [
                                    'certification' => 'PG'
                                ]
                            ]
                        ]
                    ]
                ],
                'genres' => [
                    [
                        'id' => 1,
                        'name' => 'Family'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Comedy'
                    ]
                ],
                'runtime' => 101,
                'credits' => [
                    'cast' => [
                        [
                            'name' => 'Actor'
                        ]
                    ]
                ],
                'external_ids' => [
                    'imdb_id' => '1234'
                ]
            ], 200);
        },
    ]);

    livewire('pages::explore.index', ['query' => 'Toy Story'])
        ->assertSet('search', 'Toy Story')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire('pages::explore.index')
        ->assertHasNoErrors();
});
