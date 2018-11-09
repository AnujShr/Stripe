<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Stripe\Customer;

class Subscription extends Model
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(Plan $plan, $token)
    {
        $customer = Customer::create([
            'email' => $this->user->email,
            'source' => $token,
            'plan' => $plan->name
        ]);

        $this->user->subscribe($customer->id);

    }

    public function cancel($atPeriodEnd = true)
    {
        $customer = Customer::retrieve($this->user->stripe_id);
        $subscription = $customer->cancelSubscription(['at_period_end' => $atPeriodEnd]);
        $endDate = Carbon::createFromTimestamp($subscription->current_period_end);
        $this->user->deactivate($endDate);
    }

    public function cancelImmediately()
    {
        $this->cancel(false);
    }

}
