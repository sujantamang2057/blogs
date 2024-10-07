@extends('dashboard')
@section('title', 'Detail Blog post')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    {{ Breadcrumbs::render('blog-show', $blog) }}

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item active" aria-current="page">

                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Title :</th>
                <td class="text-muted">{{ $blog->title }}</td>
            </tr>
            <tr>
                <th>Slug :</th>
                <td class="text-muted">{{ $blog->slug }}</td>
            </tr>
            <tr>
                <th>Description :</th>
                <td class="text-muted">{!! $blog->description !!}</td>
            </tr>

            <tr>
                <th>Blog Category :</th>
                <td class="text-muted">
                    @if ($blog->blogCategory)
                        {{ $blog->blogCategory->title }}
                    @else
                        none
                    @endif
                </td>
            </tr>

            <tr>
                <th>Image :</th>
                <td>
                    @if ($blog->image)
                        <a href="{{ asset('storage/images/resized/800px_' . basename($blog->image)) }}"
                            data-fancybox="gallery" data-caption="{{ $blog->title }}">
                            <img src="{{ asset('storage/images/resized/100px_' . basename($blog->image)) }}"
                                alt="{{ $blog->title }}" class="img-thumbnail" style="width: 100px; height: auto;">
                        </a>
                    @else
                        <p class="text-muted">No image available</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Published At :</th>
                <td class="text-muted">{{ $blog->published_at }}</td>
            </tr>

            <tr>
                <th>Created At :</th>
                <td class="text-muted">{{ $blog->created_at }}</td>
            </tr>

            <tr>
                <th>Created By :</th>
                @if ($blog->blogcreatedBy)
                    <td class="text-muted">{{ $blog->blogcreatedBy->name }}</td>
                @else
                    <td class="text-muted"></td>
                @endif


            </tr>

            @if ($blog->updated_at && $blog->blogupdatedBy)
                <tr>
                    <th>Updated At :</th>
                    <td class="text-muted">{{ $blog->updated_at }}</td>
                </tr>
                <tr>
                    <th>Updated By :</th>
                    @if ($blog->blogupdatedBy)
                        <td class="text-muted">{{ $blog->blogupdatedBy->name }}</td>
                    @else
                        <td class="text-muted"></td>
                    @endif


                </tr>

            @endif
            <tr>
                <th>Status :</th>
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

        </table>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('blog.index') }}" class="btn btn-warning me-2 text-white">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('blog.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Create
            </a>
            <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Update
            </a>
            <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" style="display: none;"
                id="deletePostForm">
                @csrf
                @method('DELETE')
            </form>

            <a class="btn btn-danger me-2" onclick="document.getElementById('deletePostForm').submit();">
                <i class="fas fa-trash"></i> Delete
            </a>

        </div>
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

                    // SweetAlert for confirmation
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to change the status?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to update status
                            fetch('{{ route('blog.status') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    },
                                    body: JSON.stringify({
                                        id: blogId,
                                        status: newStatus
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Show success message
                                        Swal.fire({
                                            title: 'Updated!',
                                            text: 'Status updated successfully.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            window.location.reload();
                                        });
                                    } else {
                                        // Show error message
                                        Swal.fire({
                                            title: 'Failed!',
                                            text: 'Failed to update status.',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                        // Revert the toggle if the update failed
                                        toggle.checked = !toggle.checked;
                                    }
                                })
                                .catch(error => {
                                    // Show error message
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'An error occurred.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                    console.error(error);
                                    // Revert the toggle if the update failed
                                    toggle.checked = !toggle.checked;
                                });
                        } else {
                            // Revert the toggle if the user cancels
                            this.checked = !this.checked;
                        }
                    });
                });
            });
        });
    </script>
@endsection
