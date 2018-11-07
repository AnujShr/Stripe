<?php

namespace App;

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
}
