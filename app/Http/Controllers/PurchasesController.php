<?php

namespace App\Http\Controllers;

use App\Product;
use Stripe\{Charge, Customer};

class PurchasesController extends Controller
{
    public function store()
    {
        $product = Product::query()->findorFail(request('product'));
        $customer = Customer::create([
            'email' => request('stripeEmail'),
            'source' => request('stripeToken')
        ]);
        $charge = Charge::create([
            'customer' => $customer->id,
            'amount' => $product->price,
            'currency' => 'usd'
        ]);
        return ('Done');
    }
}
