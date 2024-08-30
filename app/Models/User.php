<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Friendable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Friendable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'birthday',
        'avatar_url',
        'headshot_url',
        'bucks',
        'bits',
        'last_currency',
        'membership',
    ];

    protected $dates = [
        'avatar_render',
        'headshot_render',
        'last_online',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function avatar()
    {
        Avatar::firstOrCreate(['user_id' => $this->id]);
        return $this->hasOne(Avatar::class, 'user_id');
    }

    public function headshot()
    {
        return config('app.cdn_url').'/'.$this->headshot_url.'.png';
    }

    public function render()
    {
        return config('app.cdn_url').'/'.$this->avatar_url.'.png';
    }

    public function specials()
    {
        return Inventory::where('user_id', '=', $this->id)->where('special', '=', 1)->get();
    }

    public function isWearing(Item $item)
    {
        if($this->avatar->hat1_id == $item->id)
        {
            return true;
        } elseif($this->avatar->hat2_id == $item->id) {
            return true;
        } elseif($this->avatar->hat3_id == $item->id) {
            return true;
        } elseif($this->avatar->shirt_id == $item->id) {
            return true;
        } elseif($this->avatar->pants_id == $item->id) {
            return true;
        } elseif($this->avatar->face_id == $item->id) {
            return true;
        } elseif($this->avatar->tool_id == $item->id) {
            return true;
        } else {
            return false;
        }
    }

    public function isOnline()
    {
         if(Carbon::parse($this->last_online)->gt(Carbon::now()->subMinutes(2)))
         {
             return true;
         } else {
             return false;
         }
    }

    public function privacy()
    {
        if($this->hasOne(Privacy::class)->exists())
        {
            return $this->hasOne(Privacy::class);
        } else {
            Privacy::create(['user_id' => $this->id]);
            return $this->hasOne(Privacy::class);
        }
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->where('deleted', '=', '0');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function posts()
    {
        return $this->replies()->count() + $this->threads()->count();
    }

    public function lastThread()
    {
        return $this->hasOne(Thread::class)->where('deleted', '=', '0')->latest();
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function owns(Item $item)
    {
        return Inventory::where('item_id', $item->id)
            ->where('user_id', $this->id)
            ->exists();
    }

    public function get_membership()
    {
        $membership = "";
        if($this->membership > 0)
        {
            if($this->membership == 1)
            {
                $membership = "Bronze";
                $color = "danger";
            } elseif($this->membership == 2) {
                $membership = "Silver";
                $color = "secondary";
            } elseif($this->membership == 3) {
                $membership = "Gold";
                $color = "warning";
            }
        }
        return $membership;
    }

    public function membershipColor()
    {
        switch ($this->membership) {
            case 0:
                return 'inherit';
                break;
            case 1:
                return '#6d4c41';
                break;
            case 2:
                return '#9e9e9e';
                break;
            case 3:
                return '#fbc02d';
                break;
            case 4:
                return '#616161';
                break;
        }
    }

    public function membershipLevel()
    {
        switch ($this->membership) {
            case 0:
                return 'None';
                break;
            case 1:
                return 'Bronze VIP';
                break;
            case 2:
                return 'Silver VIP';
                break;
            case 3:
                return 'Gold VIP';
                break;
            case 4:
                return 'Platinum VIP';
                break;
        }
    }

    public function salesTax()
    {
        switch($this->membership)
        {
            case 0:
                return 0.3;
                break; 
            case 1:
                return 0.2;
                break;
            case 2:
                return 0.15;
                break;
            case 3:
                return 0.05;
                break;
        }
    }

    public function adminRank()
    {
        switch ($this->power) {
            case 1:
                return 'Moderator';
                break;
            case 2:
                return 'Moderator';
                break;
            case 3:
                return 'Administrator';
                break;
            case 4:
                return 'Executive Administrator';
                break;
            case 5:
                return 'System';
                break;
        }
    }

    public function getUserValue()
    {
        return $this->cash + $this->coins;
    }

    public function getTotalCashEarningsLastWeek()
    {
        $lastWeek = Carbon::now()->subWeek();

        $totalEarnings = DB::table('transactions')
            ->where('type', 2)
            ->where('created_at', '>=', $lastWeek)
            ->where('user_id', '=', $this->id)
            ->sum('cash');

        return $totalEarnings;
    }

    public function getTotalCoinsEarningsLastWeek()
    {
        $lastWeek = Carbon::now()->subWeek();

        $totalEarnings = DB::table('transactions')
            ->where('type', 2)
            ->where('created_at', '>=', $lastWeek)
            ->where('user_id', '=', $this->id)
            ->sum('coins');

        return $totalEarnings;
    }

    public function getTotalFutureCash()
    {
        $now = Carbon::now();

        // Fetch total cash from user_transactions where release_at is in the future
        $totalFutureCash = DB::table('transactions')
            ->where('user_id', $this->id)
            ->where('release_at', '>', $now)
            ->sum('cash');

        return $totalFutureCash;
    }

    public function getTotalFutureCoins()
    {
        $now = Carbon::now();

        // Fetch total cash from user_transactions where release_at is in the future
        $totalFutureCoins = DB::table('transactions')
            ->where('user_id', $this->id)
            ->where('release_at', '>', $now)
            ->sum('coins');

        return $totalFutureCoins;
    }

    public function grant_item(Item $item)
    {
        if($this->owns($item))
        {
            return;
        }
        return Inventory::create([
            'user_id' => $this->id,
            'item_id' => $item->id,
            'type' => $item->type,
            'collection_number' => $this->generateSerial(),
            'special' => $item->special,
        ]);
    }

    public function revoke_item(Item $item)
    {
        return Inventory::where('user_id', '=', $this->id)->where('item_id', '=', $item->id)->delete();
    }

    public function grant_currency(int $amount, $type)
    {
        if($type == 1)
        {
            return $this->update(['bucks' => $this->bucks + $amount]);
        } elseif($type == 2) {
            return $this->update(['bits' => $this->bits + $amount]);
        }
    }

    public function revoke_currency(int $amount, $type)
    {
        if($type == 1)
        {
            return $this->update(['bucks' => $this->bucks - $amount]);
        } elseif($type == 2) {
            return $this->update(['bits' => $this->bits - $amount]);
        }
    }

    public function lastIp()
    {
        $lookup = Ip::where('user_id', '=', $_SERVER['REMOTE_ADDR'])->latest();

        return $lookup;
    }

    public function ips()
    {
        $log = Ip::where('user_id', '=', $this->id)->get();
        $ips = [];

        foreach ($log as $l) {
            if (!in_array($l->ip, $ips))
                $ips[] = $l->ip;
        }

        return $ips;
    }

    public function accountsLinkedByIP()
    {
        $log = Ip::where('user_id', '!=', $this->id)->whereIn('ip', $this->ips())->get();
        $users = [];
        $times = [];

        foreach ($log as $l) {
            if (!isset($times[$l->user_id]))
                $times[$l->user_id] = 0;

            $times[$l->user_id]++;

            if (!in_array($l->user_id, $users))
                $users[] = $l->user_id;
        }

        $accounts = User::whereIn('id', $users)->get();

        foreach ($accounts as $account)
            $account->times_linked = $times[$account->id];

        return $accounts;
    }

    // non functional functions
    public function get_short_num($num) {
        if ($num < 999) {
            return $num;
        }
        else if ($num > 999 && $num <= 9999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'K+';
        }
        else if ($num > 9999 && $num <= 99999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'K+';
        }
        else if ($num > 99999 && $num <= 999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'K+';
        }
        else if ($num > 999999 && $num <= 9999999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'M+';
        }
        else if ($num > 9999999 && $num <= 99999999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'M+';
        }
        else if ($num > 99999999 && $num <= 999999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'M+';
        }
        else {
            return $num;
        }
    }
}
