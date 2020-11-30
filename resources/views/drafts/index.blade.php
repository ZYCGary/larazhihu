@extends('layouts.app')

@section('title', 'Drafts List')

@section('content')
    <div class="row mb-5">
        <div class="col-lg-9 col-md-9 topic-list">
            <div class="card ">

                <div class="card-body">
                    @if (count($drafts))
                        <ul class="list-unstyled">
                            @foreach ($drafts as $draft)
                                <li class="media">
                                    <div class="media-body">
                                        <div class="media-heading mt-1 mb-1">
                                            {{ $draft->title }}

                                            <a class="text-muted" >
                                                <i class="far fa-clock"></i>
                                                <span class="timeago">Created At：{{ $draft->created_at->diffForHumans() }}</span>
                                            </a>
                                            <a class="float-right" >
                                                <form action="/questions/{{ $draft->id }}/published-questions" method="POST" accept-charset="UTF-8">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-primary btn-sm">PUBLISH</button>
                                                </form>
                                            </a>
                                        </div>

                                    </div>
                                </li>

                                @if ( ! $loop->last)
                                    <hr>
                                @endif

                            @endforeach
                        </ul>

                    @else
                        <div class="empty-block">No Data ~_~ </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
