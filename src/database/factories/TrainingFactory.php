<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "title" => $this->faker->catchPhrase(),
            "description" => $this->faker->paragraph(),
            "tags" => ["php", "javascript", "laravel"],
            "closing_inscription_at" => Carbon::now()
        ];
    }
}
