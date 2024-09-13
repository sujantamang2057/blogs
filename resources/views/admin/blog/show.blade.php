@extends('dashboard')
@section('title', 'Detail Blog post')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary">Blog post</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog post
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Title:</th>
                <td class="text-muted">{{ $blog->title }}</td>
            </tr>
            <tr>
                <th>Description:</th>
                <td class="text-muted">{!! $blog->description !!}</td>
            </tr>

            <tr>
                <th>Blog Category:</th>
                <td class="text-muted">
                    @if ($blog->blogCategory)
                        {{ $blog->blogCategory->title }}
                    @else
                        none
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    <span class="badge {{ $blog->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $blog->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Image:</th>
                <td>
                    @if ($blog->image)
                        <a href="{{ asset('storage/' . $blog->image) }}" data-fancybox="gallery"
                            data-caption="{{ $blog->title }}">
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                                class="img-thumbnail" style="width: 100px; height: auto;">
                        </a>
                    @else
                        <p class="text-muted">No image available</p>
                    @endif
                </td>
            </tr>
        </table>

        <a href="{{ route('blog.index') }}" class="btn btn-primary mt-3">Back to List</a>
    </div>
@endsection
