<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['user_id', 'url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mesures(): HasMany
    {
        return $this->hasMany(Mesure::class);
    }
}
