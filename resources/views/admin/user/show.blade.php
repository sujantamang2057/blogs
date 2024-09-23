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
                    <th>Updated_at :</th>
                    <td class="text-muted">{{ $user->updated_at }}</td>
                </tr>
                <tr>
                    <th>Updated_by :</th>
                    @if ($user->updated_by)
                        <td class="text-muted">{{ $user->updated_by }}</td>
                    @else
                        <td class="text-muted"></td>
                    @endif


                </tr>
            </tbody>
        </table>

        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="btn me-2"
            style=" color: white;margin-top: 15px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('user.edit', $user->id) }}" class="btn me-2"
            style="background-color: red; color: white;margin-top: 15px;">
            <i class="fa-solid fa-floppy-disk"></i> Edit
        </a>
        <a href="{{ route('user.index') }}" class="btn btn-primary" style="margin-top: 15px">List</a>
    </div>
@endsection
