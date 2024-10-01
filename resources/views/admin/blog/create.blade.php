@extends('dashboard')
@section('title', 'Create blog post')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    {{ Breadcrumbs::render('blog-create') }}

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">

                        <li class="breadcrumb-item active" aria-current="page">
                            <h3 class="mb-0">Blog post</h3>
                        </li>



                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Create blog post</div>
                        </div>
                        <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <!-- Title Input -->
                                <div class="row mb-3 g-4">
                                    <div class="col-md-4">
                                        <label for="title" class="form-label"><strong>Title: <span
                                                    class="text-danger">*</span></strong></label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror" id="title"
                                            placeholder="Title" value="{{ old('title') }}">
                                        @error('title')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Parent Category Select -->

                                    <div class="col-md-4">
                                        <label for="parent_id" class="form-label"><strong>Blog Category: <span
                                                    class="text-danger">*</span></strong></label>
                                        <select class="form-control @error('blog_category_id') is-invalid @enderror"
                                            name="blog_category_id" id="parent_id">
                                            <option value="">Select blog Category</option>
                                            @foreach ($blog as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('blog_category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('blog_category_id')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror                                    
                                    </div>


                                    <div class="col-md-4">
                                        <label for="slug" class="form-label"><strong>Slug: <span
                                                    class="text-danger">*</span></strong></label>
                                        <input type="text" name="slug"
                                            class="form-control @error('title') is-invalid @enderror" id="slug"
                                            placeholder="If you leave it empty, it will be generated"
                                            value="{{ old('slug') }}">
                                        @error('title')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <!-- this is for description -->
                                <div class="row mb-3 g-4">
                                    <div class="col-md-12">
                                        <label for="description" class="form-label"><strong>Description: <span
                                                    class="text-danger">*</span></strong></label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="4" placeholder="Enter a description...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- for the published at --}}

                                <div class="row mb-3 g-4">
                                    <div class="col-md-6">
                                        <label for="published_at"><strong>Publish at:</strong></label>
                                        <input type="datetime-local" name="published_at" id="published_at"
                                            class="form-control @error('published_at') is-invalid @enderror"
                                            value="{{ old('published_at') ? \Carbon\Carbon::parse(old('published_at'))->format('Y-m-d\TH:i') : '' }}"
                                            onclick="this.showPicker()">


                                        @error('published_at')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Image Input -->
                                <div class="row mb-3 g-4">
                                    <div class="col-md-12">
                                        <label for="image" class="form-label"><strong>Image:</strong></label>
                                        <input type="file" name="image" id="image"
                                            class="form-control @error('image') is-invalid @enderror" placeholder="image">

                                        @error('image')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Status Toggle -->
                                <div class="row mb-3 g-4">
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

@section('scripts')
    <!-- Initialize FilePond -->
    <script>
        // Register FilePond plugins if necessary
        // FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginImageExifOrientation, FilePondPluginFileValidateSize);

        // Select the file input element
        const inputElement = document.querySelector('input[name="image"]');

        // Create a FilePond instance
        const pond = FilePond.create(inputElement);

        // Configure FilePond (optional)
        // pond.setOptions({
        //     server: {
        //         process: '/upload',
        //         revert: '/revert',
        //         restore: '/restore',
        //         load: '/load',
        //         fetch: '/fetch'
        //     }
        // });
    </script>
@endsection
