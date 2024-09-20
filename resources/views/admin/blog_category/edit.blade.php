@extends('dashboard')
@section('title', 'Edit Blog Category')

@section('content')
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Blog Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">Blog Category</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row g-4">
                <!--begin::Col-->
                <div class="col-12">
                    <!--begin::Quick Example-->
                    <div class="card card-primary card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Edit Blog Category</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form action="{{ route('category.update', $blogCategory->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="row mb-3">
                                    <!-- Image Input -->
                                    <div class="col-md-6">
                                        <label for="image" class="form-label"><strong>Image:</strong></label>
                                        <input type="file" name="image"
                                            class="form-control @error('image') is-invalid @enderror" id="image"
                                            placeholder="Image">
                                        @error('image')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Title Input -->
                                    <div class="col-md-6">
                                        <label for="title" class="form-label"><strong>Title:</strong></label>
                                        <input type="text" name="title"
                                            value="{{ old('title', $blogCategory->title) }}"
                                            class="form-control @error('title') is-invalid @enderror" id="title"
                                            placeholder="Title">
                                        @error('title')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Parent Category Select -->
                                    <div class="col-md-6">
                                        <label for="parent_id" class="form-label"><strong>Parent Category:</strong></label>
                                        <select class="form-control @error('parent_id') is-invalid @enderror"
                                            name="parent_id" id="parent_id">
                                            <option value="">Select Parent Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $blogCategory->parent_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status Switch -->
                                    <div class="col-md-6">
                                        <label for="status" class="form-label"><strong>Status:</strong></label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input @error('status') is-invalid @enderror"
                                                type="checkbox" role="switch" id="status" name="status" value="1"
                                                {{ old('status', $category->status) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status"></label>
                                        </div>
                                        @error('status')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->

                            <!--begin::Footer-->
                            <div class="card-footer d-flex justify-content-start">
                                <a href="{{ url()->previous() }}" class="btn btn-dark me-2">
                                    <i class="fa-solid fa-arrow-left"></i> Cancel
                                </a>
                                {{-- <a href="{{ route('category.create') }}" class="btn btn-danger me-2">
                                    <i class="fa-solid fa-plus"></i> Create
                                </a> --}}
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fa-solid fa-floppy-disk"></i> Update
                                </button>
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
                    @if (isset($blogCategory) && $blogCategory->image)
                        {
                            source: '{{ asset('storage/' . $blogCategory->image) }}',
                            options: {
                                type: 'local'
                            },
                        }
                    @endif
                ],
            });
        </script>
    @endpush

@endsection
