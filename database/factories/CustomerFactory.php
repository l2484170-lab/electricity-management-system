<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'national_id' => $this->faker->unique()->numerify('####'),
            'opening_reading' => $this->faker->randomFloat(1, 0, 5000),
            'is_active' => true,
            'is_archived' => false,
        ];
    }
}
