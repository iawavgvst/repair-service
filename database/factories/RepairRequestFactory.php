<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepairRequest>
 */
class RepairRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('ru_RU');

        $statuses = ['new', 'assigned', 'in_progress', 'done', 'canceled'];
        $status = $this->faker->randomElement($statuses);

        $assignedTo = null;
        if (in_array($status, ['assigned', 'in_progress', 'done'])) {
            $assignedTo = User::where('role', 'master')->inRandomOrder()->first()->id;
        }

        return [
            'client_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('+7 (9##) ###-##-##'),
            'address' => $this->faker->address(),
            'problem_text' => $this->faker->realText(255, 2),
            'status' => $status,
            'assigned_to' => $assignedTo,
            'created_at' => $createdAt = $this->faker->dateTimeBetween('-45 days', 'now')
                ->format('d.m.Y H:i:s'),
            'updated_at' => $this->faker->dateTimeBetween($createdAt, 'now')
                ->format('d.m.Y H:i:s'),
        ];
    }
}
