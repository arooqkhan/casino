<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = [
        'type',
        'credit',
        'campaign_id',
        'valid_from',
        'valid_until',
        'color',
        'shadow',
        'description',
    ];

    protected $casts = [
        'valid_from'  => 'datetime',
        'valid_until' => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
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
