<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InviteUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a member whose name was mentioned in a question can be notified when the question is published.
     *
     * @test
     * @covers \App\Http\Controllers\PublishedQuestionsController
     */
    public function a_member_mentioned_can_be_notified()
    {
        $questionCreator = create(User::class, ['name' => 'Gary']);
        $mentionedMember = create(User::class, ['name' => 'Nancy']);

        $this->signIn($questionCreator);

        $question = create(Question::class, [
            'user_id' => $questionCreator->id,
            'content' => 'I mention @Nancy.',
            'published_at' => null
        ]);

        $this->assertCount(0, $mentionedMember->notifications);

        $this->post(route('published-questions.store', $question));

        $this->assertCount(1, $mentionedMember->refresh()->notifications);
    }

    /**
     * Testing all the members whose name were mentioned in a question can be notified when the question is published.
     *
     * @test
     * @covers \App\Http\Controllers\PublishedQuestionsController
     */
    public function all_members_mentioned_can_be_notified()
    {
        $questionCreator = create(User::class, ['name' => 'Gary']);
        $mentionedMember1 = create(User::class, ['name' => 'Nancy']);
        $mentionedMember2 = create(User::class, ['name' => 'Lily']);

        $this->signIn($questionCreator);

        $question = create(Question::class, [
            'user_id' => $questionCreator->id,
            'content' => 'I mention @Nancy and @Lily.',
            'published_at' => null
        ]);

        $this->assertCount(0, $mentionedMember1->notifications);
        $this->assertCount(0, $mentionedMember2->notifications);

        $this->post(route('published-questions.store', $question));

        $this->assertCount(1, $mentionedMember1->refresh()->notifications);
        $this->assertCount(1, $mentionedMember2->refresh()->notifications);
    }

}
