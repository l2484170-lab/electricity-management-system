<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'position' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement(['Operations', 'Admin', 'Finance', 'Field']),
            'base_salary' => $this->faker->randomFloat(2, 2000, 10000),
            'hire_date' => $this->faker->date(),
            'is_active' => true,
        ];
    }
}
