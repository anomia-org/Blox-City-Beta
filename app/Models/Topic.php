<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Topic extends Model
{
    use HasFactory;

    public function threads()
    {
        if(Auth::check() && Auth::user()->power > 0)
        {
            return $this->hasMany(Thread::class);
        } else {
            return $this->hasMany(Thread::class)->where('deleted', '=', '0');
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function latestThread()
    {
        return $this->hasOne(Thread::class)->where('deleted', '=', '0')->latest();
    }

    public function latestReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
}
