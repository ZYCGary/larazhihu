<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewDraftsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a guest cannot view the list of question drafts.
     *
     * @test
     * @covers \App\Http\Controllers\DraftsController
     */
    public function guest_cannot_view_draft_list()
    {
        $this->withExceptionHandling();

        $this->get(route('drafts.index'))
            ->assertRedirect(route('login'));
    }

    /**
     * Testing the question creator can view the list of his/her question drafts.
     *
     * @test
     * @covers \App\Http\Controllers\DraftsController
     */
    public function creator_can_view_draft_list()
    {
        $this->signIn();

        $question = create(Question::class, [
            'published_at' => null,
            'user_id' => auth()->id()
        ]);

        $this->get(route('drafts.index'))
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /**
     * Testing only the question creator can view the list of his/her question drafts.
     *
     * A user is not authenticated to view the list of drafts created by other users.
     * For example, userA's drafts will be invisible in userB's draft list.
     *
     * @test
     * @covers \App\Http\Controllers\DraftsController
     */
    public function only_creator_can_view_his_draft_list()
    {
        $gary = create(User::class, ['name' => 'Gary']);
        $nancy = create(User::class, ['name' => 'Nancy']);

        $draftWithGary = create(Question::class, ['user_id' => $gary->id]);
        $draftWithNancy = create(Question::class, ['user_id' => $nancy->id]);

        $this->signIn($gary);

        $this->get(route('drafts.index'))
            ->assertStatus(200)
            ->assertSee($draftWithGary->title)
            ->assertDontSee($draftWithNancy->title);
    }

    /**
     * Testing the question creator cannot see published questions in his/her draft list.
     *
     * @test
     * @covers \App\Http\Controllers\DraftsController
     */
    public function cannot_see_published_questions()
    {
        $this->signIn();

        $question = create(Question::class, [
            'user_id' => auth()->id(),
            'published_at' => Carbon::now()
        ]);

        $this->get('/drafts')
            ->assertStatus(200)
            ->assertDontSee($question->title);
    }
}
