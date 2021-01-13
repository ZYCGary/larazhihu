<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

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
    #[ArrayShape(['user_id' => "\Closure", 'category_id' => "\Closure", 'title' => "string", 'content' => "string"])]
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'category_id' => function () {
                return Category::factory()->create()->id;
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
    public function published(): Factory
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
    public function unpublished(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => null,
            ];
        });
    }
}
