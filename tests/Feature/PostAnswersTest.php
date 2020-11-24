<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_post_an_answer()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $question = Question::factory()->published()->create();

        $response = $this->post("/questions/{$question->id}/answers", [
            'content' => 'This is an answer.'
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    /** @test */
    public function signed_in_user_can_post_an_answer_to_a_published_question()
    {
        $question = Question::factory()->published()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post("/questions/{$question->id}/answers", [
                'content' => 'This is an answer.'
            ]);
        $response->assertStatus(302);

        $answer = $question->answers()->where('user_id', $user->id)->first();
        $this->assertNotNull($answer);

        $this->assertEquals(1, $question->answers->count());
    }

    /** @test */
    public function cannot_post_an_answer_to_an_unpublished_question()
    {
        $question = Question::factory()->unpublished()->create();
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->post("/questions/{$question->id}/answers", [
                'content' => 'This is an answer.'
            ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('answers', ['question_id' => $question->id]);
        $this->assertEquals(0, $question->answers()->count());
    }

    /** @test */
    public function content_is_required_to_post_answer()
    {
        $this->withExceptionHandling();

        $question = Question::factory()->published()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/questions/' . $question->id . '/answers', [
                'content' => null,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('content');
    }
}
