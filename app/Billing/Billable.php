<?php

namespace App\Billing;

use App\Payment;
use App\Subscription;
use Carbon\Carbon;

trait Billable
{
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

    public function deactivate($endDate = null)
    {
        $endDate = $endDate ?: Carbon::now();
        return $this->update([
            'stripe_active' => false,
            'subscription_end_at' => $endDate
        ]);

    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('created_at', 'DESC');
    }

    public function getOptionsPaginatedAttribute()
    {
        return $this->payments()->paginate(6);
    }
}