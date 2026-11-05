<?php

namespace App\Models;

use App\Support\VideoEmbed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sermon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'preached_at' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function platform(): ?string
    {
        return VideoEmbed::platform($this->video_url);
    }

    public function embedUrl(): ?string
    {
        return VideoEmbed::embedUrl($this->video_url);
    }

    public function platformLabel(): string
    {
        return match ($this->platform()) {
            'youtube' => 'YouTube',
            'facebook' => 'Facebook',
            default => 'Lien',
        };
    }
}
