<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_id',
        'target_type', // 1: user, 2: thread
        'ip',
    ];

    public function scopeUser($query, $user)
    {
        return $query->where('target_id', $user->id)
            ->where('target_type', 1);
    }

    public function scopeThread($query, $thread)
    {
        return $query->where('target_id', $thread->id)
            ->where('target_type', 2);
    }
}
