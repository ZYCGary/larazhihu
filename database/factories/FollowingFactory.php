<?php

namespace Database\Factories;

use App\Models\Following;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class FollowingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Following::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['user_id' => "\Closure", 'question_id' => "\Closure"])]
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create();
            },
            'question_id' => function () {
                return Question::factory()->create();
            }
        ];
    }
}
