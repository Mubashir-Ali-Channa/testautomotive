<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'role',
        'avatar_path',
        'specialties',
        'is_active',
    ];

    protected $casts = [
        'specialties' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
