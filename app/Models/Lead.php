<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'plan',
        'ghl_sent_at', 'ghl_response',
    ];

    protected $casts = [
        'ghl_sent_at'  => 'datetime',
        'ghl_response' => 'array',
    ];

    public function planLabel(): string
    {
        return match ($this->plan) {
            'grupal'         => 'Acceso · Grupal',
            'transformacion' => 'Transformación · Con acompañamiento',
            'relanzamiento'  => 'Relanzamiento · Con red',
            default          => 'Desconocido',
        };
    }

    public function planAmount(): int
    {
        return match ($this->plan) {
            'grupal'         => 4500,
            'transformacion' => 9500,
            'relanzamiento'  => 18000,
            default          => 0,
        };
    }
}
