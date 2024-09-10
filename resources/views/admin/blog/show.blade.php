@extends('dashboard')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary">Blog post</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog post
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <strong>Title:</strong> <span class="text-muted">{{ $blog->title }}</span>
        </div>
        <div class="mb-3">
            <strong>Description:</strong> <span class="text-muted">{!! $blog->description !!}</span>
        </div>
        <div class="mb-3">
            <strong>Published at:</strong> <span class="text-muted">{{ $blog->published_at }}</span>
        </div>
        <div class="mb-3">
            <strong>Blog Category:</strong> <span class="text-muted">

                @if ($blog->blogCategory)
                    {{ $blog->blogCategory->title }}
                @else
                    none
                @endif

            </span>
        </div>
        <div class="mb-3">
            <strong>Status:</strong>
            <span class="badge {{ $blog->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $blog->status ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="mb-3">
            <strong>Image:</strong>
            @if ($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-thumbnail"
                    style="width: 100px; height: auto;">
            @else
                <p class="text-muted">No image available</p>
            @endif
        </div>

        <a href="{{ route('blog.index') }}" class="btn btn-primary">Back to List</a>
    </div>
@endsection
