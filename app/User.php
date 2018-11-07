<?php

namespace App;

use App\Billing\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'stripe_id', 'stripe_active', 'subscription_end_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function byStripeId($stripeId)
    {
        return static::query()->where('stripe_id', $stripeId)->firstorFail();
    }

}