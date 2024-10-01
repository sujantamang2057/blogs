@extends('dashboard')
@section('title', 'Detail User')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    {{ Breadcrumbs::render('user-show', $user) }}

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item ">
                            <h3 class="mb-0">User</h3>

                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <table class="table table-bordered table-striped">
            <thead>

            </thead>
            <tbody>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $user->email }}</td>
                </tr>

                <tr>
                    <th>Image :</th>
                    <td>
                        @if ($user->image)
                            <a href="{{ asset('storage/' . $user->image) }}" data-fancybox="gallery"
                                data-caption="{{ $user->title }}">
                                <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->title }}"
                                    class="img-thumbnail" style="width: 100px; height: auto;">
                            </a>
                        @else
                            <p class="text-muted">No image available</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong></td>
                    <td>{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th>Created At :</th>
                    <td class="text-muted">{{ $user->created_at }}</td>
                </tr>
                <tr>
                    <th>Created By :</th>
                    @if ($user->created_by)
                        <td class="text-muted">{{ $user->created_by }}</td>
                    @else
                        <td class="text-muted"></td>
                    @endif


                </tr>

                @if ($user->updated_at && $user->updated_by)
                    <tr>
                        <th>Updated At :</th>
                        <td class="text-muted">{{ $user->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated By:</th>
                        @if ($user->updated_by)
                            <td class="text-muted">{{ $user->updated_by }}</td>
                        @else
                            <td class="text-muted"></td>
                        @endif


                    </tr>
                    <tr>
                        <th>Status :</th>
                        <td>
                            @if (Auth::id() !== $user->id)
                                <label for="status{{ $user->id }}" class="form-label"><strong></strong></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox"
                                        role="switch" id="status{{ $user->id }}" name="status"
                                        data-id="{{ $user->id }}" value="1" {{ $user->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status{{ $user->id }}"></label>
                                </div>
                            @else
                                <button class="btn btn-success btn-sm">Active</button>
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('user.index') }}" class="btn btn-warning me-2 text-white">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('user.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Create
            </a>
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Update
            </a>
            @if ($user->id != Auth::user()->id)
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;"
                    id="deletePostForm">
                    @csrf
                    @method('DELETE')
                </form>

                <a class="btn btn-danger me-2" onclick="document.getElementById('deletePostForm').submit();">
                    <i class="fas fa-trash"></i> Delete
                </a>
            @endif

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
                            fetch('{{ route('user.status') }}', {
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
