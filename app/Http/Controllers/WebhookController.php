<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle()
    {
        $payload = request()->all();
        $method = $method = $this->eventToMethod($payload['type']);
        if (method_exists($this, $method)) {
            $d = $this->$method($payload);
        }
        return response('Succeess', 200);
    }

    public function whenCustomerSubscriptionDeleted($payload)
    {
        $this->retriveUser($payload['data']['object']['customer'])->deactivate();
    }

    public function whenChargeSucceeded($payload)
    {
        return $this->retriveUser($payload['data']['object']['id'])
            ->payments()
            ->create([
                'amount' => $payload['data']['object']['amount'],
                'charge_id' => $payload['data']['object']['id']
            ]);
    }

    public function whenCustomerSubscriptionCreated($payload)
    {
        return $this->retriveUser($payload['data']['object']['customer'])
            ->payments()
            ->create([
                'amount' => $payload['data']['object']['plan']['amount'],
                'plan' => $payload['data']['object']['items']['data'][0]['plan']['nickname'],
                'charge_id' => $payload['data']['object']['id']
            ]);
    }

    protected function retriveUser($payload)
    {
        return User::byStripeId($payload);

    }

    public function eventToMethod($event)
    {
        return 'when' . studly_case(str_replace('.', '_', $event));
    }
}
