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
}
