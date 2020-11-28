<?php

namespace Tests\Feature\Questions;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_questions()
    {
        // 1. 访问链接 questions
        $test = $this->get('/questions');

        // 2. 正常返回 200
        $test->assertStatus(200);
    }

    /** @test */
    public function user_can_view_a_published_question()
    {
        $question = Question::factory()->published()->create();

        $this->get(route('questions.show', ['question' => $question->id]))
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test */
    public function user_cannot_view_unpublished_question()
    {
        $this->withExceptionHandling();
        $question = Question::factory()->unpublished()->create();

        $this->withExceptionHandling()
            ->get(route('questions.show', ['question' => $question->id]))
            ->assertStatus(404);
    }

    /** @test */
    public function can_see_answers_when_view_a_published_question()
    {
        $question = Question::factory()->published()->create();
        create(Answer::class, ['question_id' => $question->id], 40);

        $response = $this->get(route('questions.show', ['question' => $question->id]));

        $result = $response->viewData('answers')->toArray();

        $this->assertCount(20, $result['data']);
        $this->assertEquals(40, $result['total']);
    }
}
