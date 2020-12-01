<?php

namespace Tests\Feature\Questions;

use App\Models\Answer;
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

        $firstPublishedQuestion = Question::published()->first();

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
     * A user has an option to view the list of questions in a specific category, if the category is included in the
     * filter
     *
     * e.g. A list of questions that are categorized as 'Testing' will be shown, if the route URL is
     * '/questions/testing'.
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
     * A user has an option to view the question list published by a specific user, if the username is included in the
     * filter.
     *
     * e.g. A list of questions published by a user named 'Gary' will be shown, when the route is '/questions?by=Gary'.
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
     * Testing a user can sort questions in terms of popularity.
     *
     * The number of answers of a question indicate its popularity. The more answers it has, the higher popularity
     * it has. A user has an option to view the question list in an descending order in terms of popularity,
     * if 'popularity=1' is passed in the URL.
     *
     * e.g. The question list will be ordered by popularity when the route is '/questions?popularity=1'.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_sort_questions_by_popularity()
    {
        // Publish a question with no answers
        $this->publishAQuestion();

        // Publish a question with 2 answers
        $questionWithTwoAnswers = $this->publishAQuestion();
        create(Answer::class, ['question_id' => $questionWithTwoAnswers->id], 2);

        // Publish a question with 3 answers
        $questionWithThreeAnswers = $this->publishAQuestion();
        create(Answer::class, ['question_id' => $questionWithThreeAnswers->id], 3);

        $response = $this->get(route('questions.index', ['popularity' => 1]));
        $questions = $response->viewData('questions')->items();

        $this->assertEquals([3, 2, 0], array_column($questions, 'popularity'));
    }

    /**
     * Testing a user can filter out questions are not answered.
     *
     * A user has an option to view the question list with only unanswered questions, if 'unanswered=1' is passed in
     * the URL.
     *
     * e.g. Only unanswered questions will shown in the question list, if the route is '/questions/unanswered=1'.
     *
     * @test
     * @covers \App\Http\Controllers\QuestionsController
     */
    public function user_can_filter_out_unanswered_questions()
    {
        // Publish a question with no answers
        $unansweredQuestion = $this->publishAQuestion();

        // Publish a question with 2 answers
        $questionWithTwoAnswers = $this->publishAQuestion();
        create(Answer::class, ['question_id' => $questionWithTwoAnswers->id], 2);

        $response = $this->get(route('questions.index', ['unanswered' => 1]));
        $result = $response->viewData('questions')->toArray();

        $this->assertEquals(1, $result['total']);
        $response->assertSee($unansweredQuestion->title);
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
