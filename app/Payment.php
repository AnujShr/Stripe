<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['amount', 'charge_id', 'user_id','plan'];

    public function inDollarFormat()
    {
        return '$' . number_format((float)$this->amount / 100, 2);
    }

}


