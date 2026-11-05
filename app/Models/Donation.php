<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gateway_response' => 'array',
        'amount' => 'decimal:2',
    ];

    public const PURPOSES = [
        'dime' => 'Dîme',
        'offrande' => 'Offrande',
        'construction' => 'Projet de construction',
        'mission' => 'Mission et évangélisation',
    ];

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    public function purposeLabel(): string
    {
        return self::PURPOSES[$this->purpose] ?? $this->purpose;
    }
}
