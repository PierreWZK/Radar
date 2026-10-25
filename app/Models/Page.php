<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = ['user_id', 'site_id', 'url', 'quotidienne'];

    protected function casts(): array
    {
        return ['quotidienne' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function mesures(): HasMany
    {
        return $this->hasMany(Mesure::class);
    }

    public function alertes(): HasMany
    {
        return $this->hasMany(Alerte::class);
    }
}
