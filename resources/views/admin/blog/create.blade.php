@extends('dashboard')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Blog post</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog post
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Create blog post</div>
                        </div>
                        <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <!-- Image Input -->
                                <div class="mb-3">
                                    <label for="image" class="form-label"><strong>Image:</strong></label>
                                    <input type="file" name="image" id="image"
                                        class="form-control @error('image') is-invalid @enderror" placeholder="image"
                                        required>
                                    @error('image')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Title Input -->
                                <div class="mb-3">
                                    <label for="title" class="form-label"><strong>Title:</strong></label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" id="title"
                                        placeholder="Title" required>
                                    @error('title')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Parent Category Select -->
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label"><strong>Blog Category:</strong></label>
                                    <select class="form-control @error('blog_category_id') is-invalid @enderror"
                                        name="blog_category_id" id="parent_id">
                                        <option value="">Select blog Category</option>
                                        @foreach ($blogCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('blog_category_id')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <!-- this is for description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label"><strong>Description:</strong></label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                        rows="4" placeholder="Enter a description...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- for the published at --}}
                                <div class="mb-3">
                                    <label for="published_at"><strong>Publish at:</strong></label>
                                    <input type="datetime-local" name="published_at" id="published_at"
                                        class="form-control @error('published_at') is-invalid @enderror"
                                        value="{{ old('published_at') ? old('published_at')->format('Y-m-d\TH:i') : '' }}">


                                    @error('published_at')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Status Toggle -->
                                <div class="mb-3">
                                    <label for="status" class="form-label"><strong>Status:</strong></label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('status') is-invalid @enderror"
                                            type="checkbox" role="switch" id="status" name="status" value="1"
                                            {{ old('status') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                    @error('status')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i>
                                    Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
