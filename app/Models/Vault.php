<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vault extends Model
{
    /** @use HasFactory<\Database\Factories\VaultFactory> */
    use HasFactory, Sluggable;

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'vault_id',
        'imdb_id',
        'vault_type',
        'title',
        'slug',
        'original_title',
        'overview',
        'backdrop_path',
        'poster_path',
        'release_date',
        'first_air_date',
        'rating',
        'genres',
        'runtime',
        'seasons',
        'actors',
        'on_wishlist',
    ];

    protected function casts(): array
    {
        return [
            'on_wishlist' => 'bool',
        ];
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
