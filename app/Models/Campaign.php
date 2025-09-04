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



}
