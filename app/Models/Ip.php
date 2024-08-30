<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'user_id',
    ];

    protected $dates = [
        'last_used_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
