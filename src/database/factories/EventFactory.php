<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->catchPhrase(),
            'description' => $this->faker->paragraph(),
            'location' => 'Alger',
            'closing_at' => Carbon::now(),
            'is_closed' => false
        ];
    }
}
