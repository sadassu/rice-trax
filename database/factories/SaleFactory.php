<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $totalPrice = $this->faker->randomFloat(2, 100, 2000);
        $amountPaid = $this->faker->randomFloat(2, $totalPrice, $totalPrice + 500);
        $change = $amountPaid - $totalPrice;

        return [
            'total_price' => $totalPrice,
            'sale_date'   => Carbon::now()->subDays(rand(0, 30)),
            'amount_paid' => $amountPaid,
            'change'      => $change,
        ];
    }
}
