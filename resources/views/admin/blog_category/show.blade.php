@extends('dashboard')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary">Blog Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog Category
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <strong>Title:</strong> <span class="text-muted">{{ $blogCategory->title }}</span>
        </div>
        <div class="mb-3">
            <strong>Slug:</strong> <span class="text-muted">{{ $blogCategory->slug }}</span>
        </div>
        <div class="mb-3">
            <strong>Status:</strong>
            <span class="badge {{ $blogCategory->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $blogCategory->status ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="mb-3">
            <strong>Image:</strong>
            @if ($blogCategory->image)
                <img src="{{ asset('uploads/' . $blogCategory->image) }}" alt="{{ $blogCategory->title }}"
                    class="img-thumbnail" style="width: 100px; height: auto;">
            @else
                <p class="text-muted">No image available</p>
            @endif
        </div>

        <a href="{{ route('category.index') }}" class="btn btn-primary">Back to List</a>
    </div>
@endsection
