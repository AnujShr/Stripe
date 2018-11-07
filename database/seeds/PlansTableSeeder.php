<?php

use App\Plan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::query()->truncate();
        $plans = [
            [
                'name' => 'monthly',
                'description' => 'A monthly plan',
                'price' => 8600
            ], [
                'name' => 'yearly',
                'description' => 'A Yearly Plan',
                'price' => 8600]
        ];
        foreach ($plans as $plan) {
            Plan::query()->create($plan);
        }
    }
}
