@extends('layouts.admin')


@section('content')

<div class="posts d-flex py-4">
    <img class="img-fluid w-50" src="{{$post->cover_image}}" alt="{{$post->title}}">

    <div class="post-data px-4">
        <h1>{{$post->title}}</h1>
        <div class="metadata">
            Category: {{$post->category ? $post->category->name : "no categories"}}
        </div>
        <div class="content">
            {{$post->content}}
        </div>
    </div>
</div>


@endsection