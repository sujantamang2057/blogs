@extends('dashboard')
@section('title', 'Edit user')

@section('content')
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    {{ Breadcrumbs::render('user-create') }}

                </div>
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
                            <div class="card-title">Create User</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!--begin::Body-->
                            <div class="card-body">
                                <!-- Image Input -->

                                <!-- Title Input -->
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label"><strong>Name: <span
                                                    class="text-danger">*</span></strong></label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            placeholder="Name">
                                        @error('name')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Slug Input (Optional) -->
                                    <!--  dexcription Input -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label"><strong>Email: <span
                                                    class="text-danger">*</span></strong></label>
                                        <input type="text" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="Email">
                                        @error('email')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row g-4 mb-3">

                                    <div class="col-md-6">
                                        <label for="image" class="form-label"><strong>Image:</strong></label>
                                        <input type="file" name="image"
                                            class="form-control @error('image') is-invalid @enderror" id="image"
                                            placeholder="Image">

                                        @error('image')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label"><strong>Phone: <span
                                                    class="text-danger">*</span></strong></label>
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror" id="phone"
                                            placeholder="Phone" value="{{ old('phone') }}">

                                        @error('phone')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label"><strong>Password:
                                            @if (true)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </strong></label>

                                    <div class="position-relative">
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            placeholder="Password">
                                        <i class="fa fa-eye-slash position-absolute" id="togglePassword"
                                            style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                    </div>

                                    @error('password')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- //for the status section --}}
                                <div class="mb-3 col-md-6">
                                    <div class="col-md-6">
                                        <label for="status" class="form-label"><strong>Status:</strong></label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input @error('status') is-invalid @enderror"
                                                type="checkbox" role="switch" id="status" name="status" value="1"
                                                {{ old('status') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status"></label>
                                        </div>
                                        @error('status')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>



                                <!-- Status Select -->

                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="row">
                                <div class="card-footer">
                                    <a href="{{ url()->previous() }}" class="btn btn-warning text-white">
                                        <i class="fas fa-times-circle"></i>Cancel
                                    </a>



                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>
                                        Submit</button>
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
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.add('fa-eye');
                icon.classList.remove('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.add('fa-eye-slash');
                icon.classList.remove('fa-eye');
            }
        });
    </script>
    @push('scripts')
        <script>
            // Initialize TinyMCE for the textarea
            initTinyMCE('#description');
        </script>


        <script>
            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.registerPlugin(FilePondPluginFileValidateType);

            const pond = FilePond.create(document.querySelector('#image'), {
                acceptedFileTypes: ['image/*'],
                server: {
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
                }
            });
            // If validation fails, reload the image in FilePond
            @if (old('image'))
                pond.addFile('{{ asset('storage/' . old('image')) }}').then(function(file) {
                    console.log('File added', file);
                });
            @endif
        </script>
    @endpush
@endsection
