<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_at',
        'end_at',
        'countdown_end',
        'terms',
        'color',
        'shadow',
    ];


    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    protected $casts = [
        'start_at'      => 'datetime',
        'end_at'        => 'datetime',
        'countdown_end' => 'datetime',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'campaign_subscribe')
            ->withTimestamps();
    }


    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'campaign_subscribe', 'campaign_id', 'user_id');
    }



    //=================please don't touch this rel by tech 
    public function winnerUser()
    {
        return $this->hasOneThrough(
            User::class,                // final model
            CampaignSubscribe::class,   // intermediate table
            'campaign_id',              // FK on campaign_subscribe
            'id',                       // PK on users
            'id',                       // PK on campaigns
            'user_id'                   // FK on campaign_subscribe
        )->where('campaign_subscribe.result', 'win');
    }
}
