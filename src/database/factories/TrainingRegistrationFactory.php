<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TrainingRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'training_id' => 1,
            'fullname' => $this->faker->name(),
            'email' => $this->faker->email(),
            'registration_number' => rand(1, 99999),
            'phone' => $this->faker->phoneNumber(),
            'course_goals' => "test",
            'study_field' => 'test'
        ];
    }
}
