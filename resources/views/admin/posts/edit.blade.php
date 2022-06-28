@extends('layouts.admin')

@section('content')

<h2 class="text-center m-4">Edit {{ $post->title }}</h2>

<div class="container w-50 pt-4 "> 
@include('partials.errors')
    <form action="{{route('admin.posts.update', $post->slug)}}" method="post" enctype="multipart/form-data"> 
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Learn php article" aria-describedby="title" value="{{old('title', $post->title)}}">
            <small id="helpId" class="text-muted">Type post title, max 150 carachters</small>
        </div>
        <div class="d-flex">
            <div class="media me-4">
                <img class="shadow" width="150" src="{{asset('storage' . $post->cover_image) }}" alt="{{$post->title}}">
            </div>
            <div class="mb-4">
                <label for="cover_image">cover_image</label>
                <input type="file" name="cover_image" id="cover_image" class="form-control  @error('cover_image') is-invalid @enderror" placeholder="Learn php article" aria-describedby="cover_imageHelper" value="{{old('cover_image', $post->cover_image)}}">
                <small id="cover_imageHelper" class="text-muted">New url of the image</small>
            </div>
        </div>
        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea class="form-control" name="content" id="content" rows="4" value="{{old('content', $post->content)}}"></textarea>
        </div>
        <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
            <option value="">Select a category</option>
            @forelse($categories as $category)

            <option value="{{$category->id}}" {{ $category->id == old('category_id', $post->category ? $post->category->id :'' )  ? 'selected' : ''}}>{{$category->name}}</option>
            @empty
            <option value="" disabled> No categories to select</option>
            @endforelse
        </select>

        <div class="mb-3 mt-3">
            <label for="tags" class="form-label">Tags</label>
            <select multiple class="form-select" name="tags[]" id="tags" aria-label="Tags">
                <!-- <option value="">Select tags</option> -->
                @forelse ($tags as $tag )

                @if($errors->any())
                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : ''}}>{{ $tag->name}} </option>
                @else
                <option value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'selected' : ''}}>{{ $tag->name}} </option>
                @endif
                @empty
                <option value="">No Tags </option>
                @endforelse

            </select>
        </div>
        <button type="submit" class="btn btn-primary text-white">Edit Post</button>

    </form>
</div>

@endsection
