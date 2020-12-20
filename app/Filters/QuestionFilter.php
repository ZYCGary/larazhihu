<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class QuestionFilter
{
    protected Request $request;
    protected Builder $queryBuilder;
    protected array $filters = ['by', 'popularity', 'unanswered'];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder): Builder
    {
        $this->queryBuilder = $builder;

        // Get filters passed from the request and exist in $this->>filters
        $filters = array_filter($this->request->only($this->filters));

        foreach ($filters as $filter => $value) {
            // $filter illustrates the method name called
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->queryBuilder;
    }

    protected function by($username): Builder
    {
        $user = User::where('name', $username)->firstOrfail();

        return $this->queryBuilder->where('user_id', $user->id);
    }

    public function popularity()
    {
        $this->queryBuilder->orderBy('popularity', 'desc');
    }

    public function unanswered()
    {
        $this->queryBuilder->where('popularity', '=', 0);
    }
}
