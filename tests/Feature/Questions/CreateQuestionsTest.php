<?php

namespace Tests\Feature\Questions;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateQuestionsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Testing a guest cannot creat a question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function guest_cannot_create_a_question()
    {
        $this->withExceptionHandling();

        $this->post(route('questions.store'))
            ->assertRedirect(route('login'));
    }

    /**
     * Testing a member can creat a new question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function member_can_create_a_question()
    {
        $this->signIn();

        $question = make(Question::class);

        $this->assertCount(0, Question::all());

        $this->post(route('questions.store', $question->toArray()))
            ->assertRedirect(route('questions.show', ['question' => Question::first()->id]));

        $this->assertCount(1, Question::all());
    }

    /**
     * Testing a user must include a 'title' in his/her question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function title_is_required()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $question = make(Question::class, ['title' => null]);

        $response = $this->post(route('questions.store', $question->toArray()));

        $response->assertRedirect();
        $response->assertSessionHasErrors('title');    }

    /**
     * Testing a user must include 'content' in his/her question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function content_is_required()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $question = make(Question::class, ['content' => null]);

        $response = $this->post(route('questions.store', $question->toArray()));

        $response->assertRedirect();
        $response->assertSessionHasErrors('content');
    }

    /**
     * Testing a user must select a 'category' for his/her question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function category_is_required_to_post_a_question()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $question = make(Question::class, ['category_id' => null]);

        $response = $this->post(route('questions.store', $question->toArray()));

        $response->assertRedirect();
        $response->assertSessionHasErrors('category_id');
    }

    /**
     * Testing a user must select a category which exists.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function category_exists()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $category = create(Category::class);

        $question = make(Question::class, [
            'category_id' => $this->faker->randomDigitNot($category->id)
        ]);

        $response = $this->post(route('questions.store', $question->toArray()));

        $response->assertRedirect();
        $response->assertSessionHasErrors('category_id');
    }

    /**
     * Testing a user cannot create questions without verifying email.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function unverified_user_cannot_create_a_question()
    {
        $this->signIn(create(User::class, ['email_verified_at' => null]));

        $question = make(Question::class);

        $this->post(route('questions.store', $question->toArray()))
            ->assertRedirect(route('verification.notice'));
    }

}
