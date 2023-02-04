<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'quantity' => $this->faker->randomNumber(3),
            'price' => $this->faker->randomDigitNot(0) * 1000,
            'company_id' => \App\Models\Company::pluck('id')->random(),
            'item_code' => "ITM-" . $this->faker->randomNumber(8),
        ];
    }
}
