<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vault extends Model
{
    /** @use HasFactory<\Database\Factories\VaultFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vault_id',
        'imdb_id',
        'vault_type',
        'title',
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
        'sort'
    ];

    protected function casts(): array
    {
        return [
            'on_wishlist' => 'bool',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
