<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'edited_at',
        'deleted_at'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'target_id')->where('target_type', '=', '3');
    }

    public function scrub()
    {
        if($this->scrubbed)
        {
            $this->update(['scrubbed' => false]);
        } else {
            $this->update(['body' => '[Content Removed]', 'scrubbed' => true]);
        }
    }
}
