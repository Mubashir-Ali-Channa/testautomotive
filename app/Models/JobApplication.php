<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'career_id',
        'name',
        'email',
        'phone',
        'resume_path',
        'message',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
