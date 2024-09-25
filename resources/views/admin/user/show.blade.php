@extends('dashboard')
@section('title', 'Detail User')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary">User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item active" aria-current="page">
                            User
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <table class="table table-bordered table-striped">
            <thead>

            </thead>
            <tbody>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $user->email }}</td>
                </tr>

                <tr>
                    <th>Image :</th>
                    <td>
                        @if ($user->image)
                            <a href="{{ asset('storage/' . $user->image) }}" data-fancybox="gallery"
                                data-caption="{{ $user->title }}">
                                <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->title }}"
                                    class="img-thumbnail" style="width: 100px; height: auto;">
                            </a>
                        @else
                            <p class="text-muted">No image available</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong></td>
                    <td>{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th>Created At :</th>
                    <td class="text-muted">{{ $user->created_at }}</td>
                </tr>
                <tr>
                    <th>Created By :</th>
                    @if ($user->created_by)
                        <td class="text-muted">{{ $user->created_by }}</td>
                    @else
                        <td class="text-muted"></td>
                    @endif


                </tr>

                @if ($user->updated_at && $user->updated_by)
                    <tr>
                        <th>Updated At :</th>
                        <td class="text-muted">{{ $user->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated By:</th>
                        @if ($user->updated_by)
                            <td class="text-muted">{{ $user->updated_by }}</td>
                        @else
                            <td class="text-muted"></td>
                        @endif


                    </tr>
                @endif
            </tbody>
        </table>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('user.index') }}" class="btn btn-warning me-2 text-white">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('user.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Create
            </a>
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Update
            </a>
            @if ($user->id != Auth::user()->id)
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;"
                    id="deletePostForm">
                    @csrf
                    @method('DELETE')
                </form>

                <a class="btn btn-danger me-2" onclick="document.getElementById('deletePostForm').submit();">
                    <i class="fas fa-trash"></i> Delete
                </a>
            @endif

        </div>
    </div>
@endsection
