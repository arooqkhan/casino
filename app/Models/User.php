<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */



    protected $guarded = [];
    // protected $fillable = [
    //     'first_name',
    //     'last_name',
    //     'email',
    //     'dob',
    //     'address',
    //     'password',
    //     'image',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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


    public function user()
    {
        return $this->belongsTo(User::class);
    }


   


    public function packages()
{
    return $this->belongsToMany(Package::class, 'package_user')
                ->withPivot('time') // extra column bhi access hoga
                ->withTimestamps();  // created_at, updated_at
}


 public function campaigns()
{
    return $this->belongsToMany(Campaign::class, 'campaign_subscribe')
                ->withTimestamps();
}

public function subscribedCampaigns()
{
    return $this->belongsToMany(Campaign::class, 'campaign_subscribe', 'user_id', 'campaign_id');
}


public function bonuses()
    {
        return $this->belongsToMany(Bonus::class, 'bonus_users', 'user_id', 'bonus_id')
                    ->withPivot('time')
                    ->withTimestamps();
    }






}
