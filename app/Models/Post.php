<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     *
     * Comment types
     * 1 = Item
     * 2 = Game
     * 3 = User
     *
     */

     protected $fillable = [
        'user_id',
        'text',
        'target_id',
        'target_type',
        'scrubbed',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'target_id')->where('target_type', '=', '4');
    }

    public function scrub()
    {
        if($this->scrubbed)
        {
            $this->update(['scrubbed' => false]);
        } else {
            $this->update(['text' => '[Content Removed]', 'scrubbed' => true]);
        }
    }
}
