<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'student_id' => 'STU-' . str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'birth_date' => $this->faker->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
            'address' => $this->faker->address(),
            'avatar' => null,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'graduated']),
            'enrollment_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'graduation_date' => null,
            'emergency_contact' => $this->faker->phoneNumber(),
            'medical_notes' => $this->faker->optional(0.3)->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}