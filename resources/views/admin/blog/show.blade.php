@extends('dashboard')
@section('title', 'Detail Blog post')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary">Blog post</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog post
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Title:</th>
                <td class="text-muted">{{ $blog->title }}</td>
            </tr>
            <tr>
                <th>Description:</th>
                <td class="text-muted">{!! $blog->description !!}</td>
            </tr>

            <tr>
                <th>Blog Category:</th>
                <td class="text-muted">
                    @if ($blog->blogCategory)
                        {{ $blog->blogCategory->title }}
                    @else
                        none
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    <label for="status{{ $blog->id }}" class="form-label"><strong></strong></label>
                    <div class="form-check form-switch">
                        <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox" role="switch"
                            id="status{{ $blog->id }}" name="status" data-id="{{ $blog->id }}" value="1"
                            {{ $blog->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="status{{ $blog->id }}"></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Image:</th>
                <td>
                    @if ($blog->image)
                        <a href="{{ asset('storage/' . $blog->image) }}" data-fancybox="gallery"
                            data-caption="{{ $blog->title }}">
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                                class="img-thumbnail" style="width: 100px; height: auto;">
                        </a>
                    @else
                        <p class="text-muted">No image available</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Published_at :</th>
                <td class="text-muted">{{ $blog->published_at }}</td>
            </tr>

            <tr>
                <th>Created_at :</th>
                <td class="text-muted">{{ $blog->created_at }}</td>
            </tr>
            <tr>
                <th>Updated_at :</th>
                <td class="text-muted">{{ $blog->updated_at }}</td>
            </tr>

        </table>
        <a href="{{ route('blog.create') }}" class="btn me-2"
            style="background-color: red; color: white;margin-top: 15px;">
            <i class="fa-solid fa-floppy-disk"></i> Create
        </a>
        <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" class="btn me-2"
            style=" color: white;margin-top: 15px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('blog.edit', $blog->id) }}" class="btn me-2"
            style="background-color: red; color: white;margin-top: 15px;">
            <i class="fa-solid fa-floppy-disk"></i> Edit
        </a>



        <a href="{{ route('blog.index') }}" class="btn btn-primary mt-3">Back to List</a>
    </div>
    <style>
        /* Set a fixed width for the first column (attribute column) */
        .table th {
            width: 200px;
            /* Adjust this width as necessary */
            white-space: nowrap;
        }

        /* Ensure the content in the second column doesn't overflow */
        .table td img {
            max-width: 100%;
            /* Ensure images don't overflow the cell */
            height: auto;
        }

        .table td {
            word-wrap: break-word;
            /* Break long words if necessary */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle toggle switch click
            document.querySelectorAll('.form-check-input').forEach(function(toggle) {
                toggle.addEventListener('change', function() {
                    var blogId = this.dataset.id;
                    var newStatus = this.checked ? 1 : 0;
                    console.log('Blog ID:', blogId);
                    console.log('New Status:', newStatus);


                    if (confirm('Are you sure you want to change the status?')) {
                        // Send AJAX request to update status

                        fetch('{{ route('blog.status') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    id: blogId,
                                    status: newStatus
                                })
                            })


                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Status updated successfully');
                                } else {
                                    alert('Failed to update status');
                                    // Revert the toggle if the update failed
                                    toggle.checked = !toggle.checked;
                                }
                            })
                            .catch(error => {
                                alert('An error occurred');
                                console.error(error);
                            });
                    } else {
                        // Revert the toggle if the user cancels
                        this.checked = !this.checked;
                    }
                });
            });
        });
    </script>
@endsection
