@extends('dashboard')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary">User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            User
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <strong>Name:</strong> <span class="text-muted">{{ $user->name }}</span>
        </div>
        <div class="mb-3">
            <strong>Email:</strong> <span class="text-muted">{{ $user->email }}</span>
        </div>





        <a href="{{ route('user.index') }}" class="btn btn-primary">Back to List</a>
    </div>
@endsection
