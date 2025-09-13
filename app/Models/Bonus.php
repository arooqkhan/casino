<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
       protected $fillable = [
        'type',
        'campaign_id',
        'valid_from',
        'valid_until',
        'color',
        'shadow',
        'description',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

     public function users()
    {
        return $this->belongsToMany(User::class, 'bonus_users', 'bonus_id', 'user_id')
                    ->withPivot('time')
                    ->withTimestamps();
    }
    
}
