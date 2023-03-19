<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => $this->faker->lastName(),
            'rate' => $this->faker->randomDigitNotZero() * 1000,
            'company_id' => \App\Models\Company::pluck('id')->random(),
            'employee_code' => "EMPL-" . $this->faker->randomNumber(8),
        ];
    }

}
