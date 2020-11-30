<?php

namespace Tests\Feature\Questions;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing a user can see the list of all published questions without filter.
     *
     * By default, no filter will be applied for the question list.
     * Only published questions are listed while unpublished questions are invisible.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_see_all_published_questions_without_filter()
    {
        create(Question::class, ['published_at' => Carbon::now()], 30);
        $unpublishedQuestion = create(Question::class);

        $firstPublishedQuestion = Question::published()->find(1);

        $response = $this->get(route('questions.index'));

        $response->assertStatus(200)
            ->assertSee($firstPublishedQuestion->title)
            ->assertDontSee($unpublishedQuestion->title);

        $result = $response->viewData('questions')->toArray();
        $this->assertEquals(30, $result['total']);
        $this->assertCount(20, $result['data']);
    }

    /**
     * Testing a user can filter questions by category.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_filter_questions_by_category()
    {
        $category = create(Category::class);

        $questionInCategory = $this->publishAQuestion(['category_id' => $category->id]);
        $questionNotInCategory = $this->publishAQuestion();

        $response = $this->get(route('questions.index', ['category' => $category->slug]));

        $response->assertStatus(200);
        $response->assertSee($questionInCategory->title);
        $response->assertDontSee($questionNotInCategory->title);
    }

    /**
     * Testing a user can filter questions by username.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_filter_questions_by_username()
    {
        $gary = create(User::class, ['name' => 'Gary']);

        $questionByGary = $this->publishAQuestion(['user_id' => $gary->id]);
        $questionNotByGary = $this->publishAQuestion();

        $response = $this->get(route('questions.index', ['by' => $gary->name]));

        $response->assertStatus(200);
        $response->assertSee($questionByGary->title);
        $response->assertDontSee($questionNotByGary->title);
    }


    /**
     * Create a published question.
     *
     * @param array $attributes A set of attributes used to override default attributes.
     * @return Collection|Model|mixed
     */
    private function publishAQuestion($attributes = [])
    {
        return Question::factory()->published()->create($attributes);
    }

}
