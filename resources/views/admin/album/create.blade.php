@extends('dashboard')
@section('title', 'Create blog post')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">

                    <h1>test album</h1>
                    <p class="text-muted">Create a new album</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">

                        <li class="breadcrumb-item active" aria-current="page">
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
                            <div class="card-title">Create Album</div>
                        </div>
                        <form action="{{ route('Album.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <!-- Title Input -->
                                <div class="row mb-3 g-4">
                                    <div class="col-md-6">
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


                                </div>


                                <div class="row mb-3 g-4">


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
                                        <label for="date"><strong>Publish at:</strong></label>
                                        <input type="datetime-local" name="date" id="date"
                                            class="form-control @error('published_at') is-invalid @enderror"
                                            value="{{ old('date') ? \Carbon\Carbon::parse(old('date'))->format('Y-m-d\TH:i') : '' }}"
                                            onclick="this.showPicker()">


                                        @error('date')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Image Input -->

                                <!-- Status Toggle -->
                                <div class="row mb-3 g-4">
                                    <div class="col-md-12">
                                        <label for="image" class="form-label"><strong>Image:</strong></label>
                                        <input type="file" name="image[]" id="image"
                                            class="form-control @error('image') is-invalid @enderror" placeholder="image"
                                            multiple>
                                        @error('image')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                        <input type="hidden" name="imagePaths" id="imagePaths">
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
                allowMultiple: true, // Enable multiple file uploads
                server: {
                    process: {
                        url: '{{ route('multipleUpload') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        onload: (response) => {
                            const data = JSON.parse(response);
                            const input = document.querySelector('#imagePaths');

                            // Get current images or initialize an empty array
                            let existingImages = input.value ? JSON.parse(input.value) : [];

                            // Append the newly uploaded image paths to the array
                            data.paths.forEach((path) => {
                                existingImages.push(path);
                            });

                            // Update the hidden input with new image paths as a JSON string
                            input.value = JSON.stringify(existingImages);
                            return data.paths;
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
