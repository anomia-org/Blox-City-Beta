<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'inventory',
        'fillable',
        'trade',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
