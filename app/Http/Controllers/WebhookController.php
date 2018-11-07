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
            $this->$method($payload);
        }

        return response('Succeess', 200);
    }

    public function whenCustomerSubscriptionDeleted($payload)
    {
        $user = User::
            byStripeId($payload['data']['object']['customer'])
            ->deactivate();
    }

    public function eventToMethod($event)
    {
        return 'when' . studly_case(str_replace('.', '_', $event));
    }
}
