@extends('dashboard')
@section('title', 'Edit user')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    {{ Breadcrumbs::render('user-update', $user) }}

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">
                            <h3 class="mb-0">User</h3>

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
                            <div class="card-title">Edit User</div>
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
                                <div class="row">
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
                                            placeholder="Title">
                                        @error('email')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <label for="image" class="form-label"><strong>Image:</strong></label>
                                        <input type="file" name="image"
                                            class="form-control @error('image') is-invalid @enderror" id="image"
                                            placeholder="image">

                                        @error('image')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label"><strong>Phone:</strong></label>
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror" id="phone"
                                            placeholder="phone" value="{{ old('phone', $user->phone) }}">

                                        @error('phone')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if (Auth::id() !== $user->id)
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="status" class="form-label"><strong>Status:</strong></label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input @error('status') is-invalid @enderror"
                                                    type="checkbox" role="switch" id="status" name="status"
                                                    value="1" {{ old('status', $user->status) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status"></label>
                                            </div>
                                            @error('status')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif



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
    @push('scripts')
        <script>
            // Register the FilePond plugins
            FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);

            // Get the image input element
            const inputElement = document.querySelector('#image');

            // Initialize FilePond
            const pond = FilePond.create(inputElement, {
                acceptedFileTypes: ['image/*'],
                server: {
                    load: (source, load, error, progress, abort, headers) => {
                        fetch(source, {
                            mode: 'cors'
                        }).then((res) => {
                            return res.blob();
                        }).then(load).catch(error);
                    },
                    process: {
                        url: '{{ route('upload') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        onload: (response) => {
                            const data = JSON.parse(response);
                            return data.path;
                        }
                    },
                    revert: {
                        url: '{{ route('revert') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                },
                files: [
                    @if (isset($user) && $user->image)
                        {
                            source: '{{ asset('storage/' . $user->image) }}',
                            options: {
                                type: 'local',
                            },
                        }
                    @endif
                ],
            });
        </script>
    @endpush
@endsection
