@extends('dashboard')
@section('title', 'Edit user')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    {{ Breadcrumbs::render('user-update', $user) }}

                </div>
                @include('admin.message.alert')

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">

                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row g-4">
                <!--begin::Col-->
                <div class="col-12"></div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-12">
                    <!--begin::Quick Example-->
                    <div class="card card-primary card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Edit password</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form action="{{ route('user.password.change', $user->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!--begin::Body-->
                            <div class="card-body">
                                <!-- Image Input -->

                                <!-- Title Input -->
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label"><strong>Name:</strong></label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            placeholder="Name">
                                        @error('name')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Slug Input (Optional) -->
                                    <!--  dexcription Input -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label"><strong>Email:</strong></label>
                                        <input type="text" name="email" value="{{ old('email', $user->email) }}"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="Title" {{ Auth::id() === $user->id ? 'readonly' : '' }}>
                                        @error('email')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>

                                <div class="row ">
                                    @if (Auth::id() === $user->id)
                                        <div class="col-md-6">
                                            <label for="email" class="form-label"><strong>Currrent
                                                    Password:</strong></label>
                                            <input type="text" name="current_password"
                                                value="{{ old('current_password') }}"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                id="current_password" placeholder="Current Password">
                                            @error('current_password')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>



                                        <div class="col-md-6">
                                            <label for="email" class="form-label"><strong>New Password:</strong></label>
                                            <input type="text" name="new_password" value="{{ old('new_password') }}"
                                                class="form-control @error('new_password') is-invalid @enderror"
                                                id="current_password" placeholder="New Password">
                                            @error('new_password')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            <label for="email" class="form-label"><strong>New Password:</strong></label>
                                            <input type="text" name="new_password" value="{{ old('new_password') }}"
                                                class="form-control @error('new_password') is-invalid @enderror"
                                                id="current_password" placeholder="New Password">
                                            @error('new_password')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label"><strong> Confirm
                                                    Password:</strong></label>
                                            <input type="text" name="confirm_password"
                                                value="{{ old('confirm_password') }}"
                                                class="form-control @error('new_password') is-invalid @enderror"
                                                id="confirm_password" placeholder="Confirm Password">
                                            @error('confirm_password')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif




                                </div>



                                <!-- Status Select -->

                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="row">

                                <div class="card-footer">
                                    <a href="{{ route('user.index') }}" class="btn btn-warning text-white">
                                        <i class="fas fa-times-circle"></i> Cancel</a>

                                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i>
                                        Update</button>

                                </div>
                            </div>
                            <!--end::Footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Quick Example-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->

@endsection
