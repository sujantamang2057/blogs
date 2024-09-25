@extends('dashboard')

@section('title', 'Create Blog Category')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Blog Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog Category
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Create Blog Category</div>
                        </div>
                        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row mb-3 g-4">
                                    <!-- Title Input -->
                                    <div class="col-md-4">
                                        <label for="title" class="form-label"><strong>Title: <span
                                                    class="text-danger">*</span></strong></label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror" id="title"
                                            placeholder="Title">
                                        @error('title')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Parent Category Select -->
                                    <div class="col-md-4">
                                        <label for="parent_id" class="form-label"><strong>Parent Category:</strong></label>
                                        <select class="form-control @error('parent_id') is-invalid @enderror"
                                            name="parent_id" id="parent_id">
                                            <option value="">Select Parent Category</option>
                                            @foreach ($blogCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- for the slug --}}
                                    <div class="col-md-4">
                                        <label for="slug" class="form-label"><strong>Slug: <span
                                                    class="text-danger">*</span></strong></label>
                                        <input type="text" name="slug"
                                            class="form-control @error('title') is-invalid @enderror" id="slug"
                                            placeholder="If you leave it empty, it will be generated">
                                        @error('slug')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3 g-4">
                                    <!-- Image Input -->
                                    <div class="col-md-6">
                                        <label for="image" class="form-label"><strong>Image:</strong></label>
                                        <input type="file" name="image" id="image"
                                            class="form-control @error('image') is-invalid @enderror" placeholder="image">
                                        @error('image')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status Toggle -->
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
                            </div>

                            <div class="row">
                                <div class="card-footer">
                                    <a href="{{ url()->previous() }}" class="btn btn-warning text-white">
                                        <i class="fas fa-times-circle"></i>Cancel
                                    </a>



                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>
                                        Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
