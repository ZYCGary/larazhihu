<?php

namespace Tests\Feature\Questions;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Contract\FormValidationContractTest;
use Tests\TestCase;

class CreateQuestionsTest extends TestCase
{
    use RefreshDatabase;
    use FormValidationContractTest;
    use WithFaker;

    /** @test */
    public function guest_cannot_create_question()
    {
        $this->withExceptionHandling();

        $this->post(route('questions.store'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_new_questions()
    {
        $this->signIn();

        $question = make(Question::class);

        $this->assertCount(0, Question::all());

        $this->post(route('questions.store', $question->toArray()))
            ->assertRedirect(route('questions.show', ['question' => Question::first()->id]));

        $this->assertCount(1, Question::all());
    }

    /** @test */
    public function title_is_required_to_post_a_question()
    {
        $this->attribute_is_required('questions.store', Question::class, 'title');
    }

    /** @test */
    public function content_is_required_to_post_a_question()
    {
        $this->attribute_is_required('questions.store', Question::class, 'content');
    }

    /** @test */
    public function category_id_is_required_to_post_a_question()
    {
        $this->attribute_is_required('questions.store', Question::class, 'category_id');
    }

    /** @test */
    public function category_id_exists()
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

    /** @test */
    public function unverified_user_cannot_create_questions()
    {
        $this->signIn(create(User::class, ['email_verified_at' => null]));

        $question = make(Question::class);

        $this->post(route('questions.store', $question->toArray()))
            ->assertRedirect(route('verification.notice'));
    }

}
