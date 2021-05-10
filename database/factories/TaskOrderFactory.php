<?php

namespace Database\Factories;

use App\Models\TaskOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
        return [
            'name' => $this->faker->name,
            'deposit' => rand(-99999,99999),
        ];
    }
}
