@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mt-4">All Posts</h1>
    <div><a href="{{route('admin.posts.create')}}" class="btn btn-primary text-white my-4">Add Post</a></div>


    <!-- feedback -->
    @if (session('message'))
        <div class="alert alert-success mt-4">
            {{ session('message') }}
        </div>
    @endif

    <table class="table table-striped table-inverse table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Cover Image</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td scope="row ">{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->slug }}</td>
                    <td><img width="150px" src="{{ $post->cover_image }}" alt="" srcset=""></td>
                    <td>
                        <a href="{{ route('admin.posts.show', $post->slug) }}" class="btn btn-primary text-white">View</a>
                        <a href="{{ route('admin.posts.edit', $post->slug) }}" class="btn btn-dark">Edit</a>
                        
                        <!-- modal button trigger for post delete -->
                        <button type="button" class="btn btn-danger btn" data-bs-toggle="modal" data-bs-target="#delete-post-{{$post->id}}">
                          Delete
                        </button>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="delete-post-{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="modelTitle-{{$post->id}}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete current post</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                        <form action="{{route('admin.posts.destroy', $post->slug)}}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        



                    </td>
                </tr>

                @empty 
                <tr>
                    <td scope="row">Create your first post</td>
                </tr>
                @endforelse
            </tbody>
    </table>
    
</div>
@endsection