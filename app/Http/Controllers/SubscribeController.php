<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationFormRequest;
use App\Plan;
use Exception;

class SubscribeController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('subscribe', compact('plans'));
    }

    public function store(RegistrationFormRequest $form)
    {
        try {
            $form->save();
        } catch (Exception $e) {
            return response()->json(['status' => $e->getMessage()], 422);
        }
        return [
            'status' => 'success'
        ];

    }

}
