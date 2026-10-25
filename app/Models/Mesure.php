<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mesure extends Model
{
    protected $fillable = [
        'page_id', 'strategie', 'performance', 'accessibilite', 'bonnes_pratiques', 'seo',
        'lcp_ms', 'cls', 'inp_ms', 'conseils', 'mesuree_le',
    ];

    protected function casts(): array
    {
        return [
            'mesuree_le' => 'datetime',
            'cls' => 'float',
            'conseils' => 'array',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
