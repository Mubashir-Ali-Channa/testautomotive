<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'title',
        'department',
        'description',
        'requirements',
        'type',
        'is_active',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
