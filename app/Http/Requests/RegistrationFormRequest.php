<?php

namespace App\Http\Requests;

use App\Plan;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stripeEmail' => 'required|email',
            'stripeToken' => 'required',
            'plan' => 'required'
        ];
    }

    public function save()
    {
        $plan = Plan::query()->findorFail($this->plan);
        $this->user()
            ->subscription()
            ->create($plan, $this->stripeToken);

    }
}
