@extends('layouts.app')

@section('title', $question->title)

@section('content')
    <div class="row" >
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 question-content">
            <div class="card">
                <div class="card-body">
                    <h1 class="mt-3 mb-3">
                        {{ $question->title }}
                    </h1>

                    <div class="question-body mt-4 mb-4">
                        {!! $question->content !!}
                    </div>
                </div>
            </div>

            {{-- 答案列表 --}}
            <div class="card question-reply mt-4">
                @includeWhen(Auth::check(),'questions._answer_box', ['question' => $question])
                @if(count($answers) > 0)
                    <div class="card-body">
                        @include('questions._answer_list', ['answers' => $answers])

                        <div class="mt-5">
                            {!! $answers->appends(Request::except('page'))->render() !!}
                        </div>
                    </div>
                @else
                    <div class="empty-block">No answer yet~</div>
                @endif
            </div>

        </div>

        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
            <div class="card ">
                <div class="card-body">
                    <div class="text-center">
                        Creator: {{ $question->creator->name }}
                    </div>
                    <hr>
                    <div class="media">
                        <div align="center">
                            <a href="#">
                                <img class="thumbnail img-fluid" src="https://cdn.learnku.com/uploads/images/202005/26/19192/mX9UwDEO9g.png!large" width="300px" height="300px"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
