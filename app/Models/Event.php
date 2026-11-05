<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    /** clé => [singulier, pluriel, route de la rubrique] */
    public const TYPES = [
        'actualite' => ['Actualité', 'Actualités', 'events.actualites'],
        'retraite' => ['Retraite', 'Retraites', 'events.retraites'],
        'seminaire' => ['Séminaire', 'Séminaires', 'events.seminaires'],
        'evenement' => ['Événement', 'Événements', 'events.index'],
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type][0] ?? 'Événement';
    }
}
