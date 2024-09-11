@extends('dashboard')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">user</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            user
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
                <div class="col-md-8">
                    <!--begin::Quick Example-->
                    <div class="card card-primary card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Edit Post Category</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!--begin::Body-->
                            <div class="card-body">
                                <!-- Image Input -->

                                <!-- Title Input -->
                                <div class="mb-3">
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
                                <div class="mb-3">
                                    <label for="email" class="form-label"><strong>Email:</strong></label>
                                    <input type="text" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        placeholder="Title">
                                    @error('email')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <!-- Status Select -->

                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="row">

                                <div class="card-footer">
                                    <a href="{{ url()->previous() }}" class="btn me-2"
                                        style="background-color: black; color: white;">
                                        <i class="fa-solid fa-floppy-disk"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i>
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
