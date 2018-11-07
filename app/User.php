<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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

    public function subscribe($customerId)
    {
        return $this->update([
            'stripe_id' => $customerId,
            'stripe_active' => true,
            'subscription_end_at' => null
        ]);
    }

    public function isSubscribed()
    {
        return !!$this->stripe_active;
    }

    public function subscription()
    {
        return new Subscription($this);
    }

    public function deactivate()
    {
        return $this->update([
            'stripe_active' => false,
            'subscription_end_at' => Carbon::now()
        ]);

    }

    public static function byStripeId($stripeId)
    {
        return static::query()->where('stripe_id', $stripeId)->firstorFail();
    }
}