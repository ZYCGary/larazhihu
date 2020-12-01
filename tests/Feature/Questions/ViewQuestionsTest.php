<?php

namespace Tests\Feature\Questions;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a user can view the question list.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_view_questions()
    {
        $test = $this->get(route('questions.index'));

        $test->assertStatus(200);
    }

    /**
     * Testing a user can view the detail of a published question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_view_a_published_question()
    {
        $category = create(Category::class);
        $question = Question::factory()->published()->create(['category_id' => $category->id]);

        $this->get(route('questions.show', [
            'category' => $category->slug,
            'question' => $question->id
        ]))
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /**
     * Testing a user cannot view the detail of an unpublished question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_cannot_view_an_unpublished_question()
    {
        $this->withExceptionHandling();

        $category = create(Category::class);
        $question = Question::factory()->unpublished()->create([
            'category_id' => $category->id
        ]);

        $this->withExceptionHandling()
            ->get(route('questions.show', [
                'category' => $category->slug,
                'question' => $question->id
            ]))
            ->assertStatus(404);
    }

    /**
     * Testing a user can see answers when viewing a published question.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_see_answers_when_viewing_a_published_question()
    {
        $category = create(Category::class);
        $question = Question::factory()->published()->create(['category_id' => $category->id]);
        create(Answer::class, ['question_id' => $question->id], 40);

        $response = $this->get(route('questions.show', [
            'category' => $category->slug,
            'question' => $question->id
        ]));

        $result = $response->viewData('answers')->toArray();

        $this->assertCount(20, $result['data']);
        $this->assertEquals(40, $result['total']);
    }
}
