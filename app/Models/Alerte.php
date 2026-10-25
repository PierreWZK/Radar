<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerte extends Model
{
    protected $fillable = ['page_id', 'strategie', 'raison', 'performance_avant', 'performance_apres', 'conseils'];

    protected function casts(): array
    {
        return ['conseils' => 'array'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
