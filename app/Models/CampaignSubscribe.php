<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSubscribe extends Model
{
    use HasFactory;

    protected $table = 'campaign_subscribe';

    protected $guarded = [];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
