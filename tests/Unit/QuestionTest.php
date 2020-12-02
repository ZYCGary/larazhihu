<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Following;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a question has many answers.
     *
     * Testing one-to-many relationship with Answer.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_has_many_answers()
    {
        $question = Question::factory()->create();
        create(Answer::class, ['question_id' => $question->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $question->answers());
    }

    /**
     * Testing a question is published only if attribute 'published_at' is not null.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function questions_with_published_at_date_are_published()
    {
        $publishedQuestion1 = Question::factory()->published()->create();
        $publishedQuestion2 = Question::factory()->published()->create();
        $unpublishedQuestion = Question::factory()->create();

        $publishedQuestions = Question::published()->get();
        $this->assertTrue($publishedQuestions->contains($publishedQuestion1));
        $this->assertTrue($publishedQuestions->contains($publishedQuestion2));
        $this->assertFalse($publishedQuestions->contains($unpublishedQuestion));
    }

    /**
     * Testing a question can mark one of its answer as the best answer.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function can_mark_an_answer_as_best()
    {
        $question = create(Question::class, ['best_answer_id' => null]);
        $answer = create(Answer::class, ['question_id' => $question->id]);

        $question->markAsBest($answer);

        $this->assertEquals($question->best_answer_id, $answer->id);

    }

    /**
     * Testing a question belongs to a creator.
     *
     * Testing many-to-one relationship with User.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_belongs_to_a_creator()
    {
        $question = create(Question::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $question->creator());
        $this->assertInstanceOf('App\Models\User', $question->creator);
    }

    /**
     * Testing a question can be published.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_can_be_published()
    {
        $question = create(Question::class, ['published_at' => null]);

        $this->assertCount(0, Question::published()->get());

        $question->publish();

        $this->assertCount(1, Question::published()->get());
    }

    /**
     * Testing a question can detect all mentioned usernames from the content.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_can_detect_all_mentioned_users()
    {
        $question = create(Question::class, [
            'content' => 'I mentioned @Nancy and @Lily.'
        ]);

        $this->assertEquals(['Nancy', 'Lily'], $question->mentionedUsers());
    }

    /**
     * Test a questions is a draft if the attribute 'published_at' is null.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function questions_without_published_at_data_are_drafts()
    {
        $user = create(User::class);

        $draft1 = create(Question::class, [
            'user_id' => $user->id,
            'published_at' => null
        ]);
        $draft2 = create(Question::class, [
            'user_id' => $user->id,
            'published_at' => null
        ]);
        $publishedQuestion = create(Question::class, [
            'user_id' => $user->id,
            'published_at' => Carbon::now()
        ]);

        $drafts = Question::drafts($user)->get();

        $this->assertTrue($drafts->contains($draft1));
        $this->assertTrue($drafts->contains($draft2));
        $this->assertFalse($drafts->contains($publishedQuestion));
    }

    /**
     * Testing a questions can increment its popularity when an answer created.
     *
     * 'popularity' attribute illustrates the number of answers to this question. Once an answer created, the
     * question popularity increments.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_has_popularity_attribute()
    {
        $question = create(Question::class);
        $this->assertEquals(0, $question->refresh()->popularity);

        create(Answer::class, ['question_id' => $question->id]);

        $this->assertEquals(1, $question->refresh()->popularity);
    }

    /**
     * Testing a question can have many followers.
     *
     * Testing one-to-many relationship with Following.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_has_many_followers()
    {
        $question = create(Question::class);

        create(Following::class, ['question_id' => $question->id], 2);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $question->followers());
    }

    /**
     * Testing a question can be followed by a given user.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_can_be_followed_by_a_user()
    {
        $user = create(User::class);

        $question = create(Question::class);

        $question->followedBy($user->id);

        $this->assertCount(1, $question->followers()->where(['user_id' => $user->id])->get());
    }

    /**
     * Testing a question can be unfollowed by a given user.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_can_be_unfollowed_by_a_user()
    {
        $user = create(User::class);
        $question = create(Question::class);

        create(Following::class, [
            'user_id' => $user->id,
            'question_id' => $question->id
        ]);

        $this->assertCount(1, $question->followers()->where(['user_id' => $user->id])->get());

        $question->unfollowedBy($user->id);

        $this->assertCount(0, $question->followers()->where(['user_id' => $user->id])->get());
    }

    /**
     * Testing a question can add an answer.
     *
     * @test
     * @covers \App\Models\Question
     */
    public function a_question_can_add_an_answer()
    {
        $question = create(Question::class);

        $question->addAnAnswer([
            'user_id' => create(User::class)->id,
            'content' => 'This is content.'
        ]);

        $this->assertCount(1, $question->refresh()->answers()->get());
    }

}
