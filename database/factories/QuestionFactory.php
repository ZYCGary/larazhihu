<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'title' => $this->faker->sentence,
            'content' => $this->faker->text
        ];
    }

    /**
     * Indicate that the question is published.
     *
     * @return Factory
     */
    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => Carbon::parse('-1week'),
            ];
        });
    }

    /**
     * Indicate that the question is unpublished.
     *
     * @return Factory
     */
    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => null,
            ];
        });
    }
}
