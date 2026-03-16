<?php

namespace Database\Factories;

use App\Models\Meter;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterFactory extends Factory
{
    protected $model = Meter::class;

    public function definition(): array
    {
        return [
            'meter_number' => 'MTR-' . $this->faker->unique()->numerify('######'),
            'customer_id' => Customer::factory(),
            'status' => 'active',
            'meter_type' => $this->faker->randomElement(['analog', 'digital', 'smart']),
            'location' => $this->faker->address(),
        ];
    }
}
