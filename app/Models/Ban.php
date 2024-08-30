<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'banned_by',
        'length',
        'reason',
        'note',
        'internal_note',
        'content',
        'active',
    ];

    protected $dates = [
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
