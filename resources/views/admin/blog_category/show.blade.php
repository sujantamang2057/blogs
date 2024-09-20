@extends('dashboard')
@section('title', 'Detail Blog Category')

@section('content')
    <div class="app-content-header bg-light py-3 mb-4 border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary">Blog Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog Category
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white p-4 rounded shadow-sm">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th scope="row">Title</th>
                    <td class="text-muted">{{ $blogCategory->title }}</td>
                </tr>
                <tr>
                    <th scope="row">Slug</th>
                    <td class="text-muted">{{ $blogCategory->slug }}</td>
                </tr>
                <tr>
                    <th scope="row">parent Category</th>
                    @if ($blogCategory->parentCategory)
                        <td class="text-muted">{{ $blogCategory->parentCategory->title }}</td>
                    @else
                        <td class="text-muted"> </td>
                    @endif
                </tr>



                <tr>
                    <th scope="row">Image</th>
                    <td>
                        @if ($blogCategory->image)
                            <a href="{{ asset('storage/' . $blogCategory->image) }}" data-fancybox="gallery"
                                data-caption="{{ $blogCategory->title }}">
                                <img src="{{ asset('storage/' . $blogCategory->image) }}" alt="{{ $blogCategory->title }}"
                                    class="img-thumbnail" style="width: 100px; height: auto;">
                            </a>
                        @else
                            <p class="text-muted">No image available</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th scope="row">Created_at</th>
                    <td class="text-muted">{{ $blogCategory->created_at }}</td>
                </tr>
                <tr>
                    <th scope="row">Created_by</th>
                    @if ($blogCategory->categorycreatedBy)
                        <td class="text-muted">{{ $blogCategory->categorycreatedBy->name }}</td>
                    @else
                        <td class="text-muted"></td>
                    @endif
                <tr>





                <tr>
                    <th scope="row">Updated_at</th>
                    <td class="text-muted">{{ $blogCategory->updated_at }}</td>
                </tr>
                <tr>
                    <th>Updated_by :</th>
                    @if ($blogCategory->categoryupdatedBy)
                        <td class="text-muted">{{ $blogCategory->categoryupdatedBy->name }}</td>
                    @else
                        <td class="text-muted"></td>
                    @endif


                </tr>
                <tr>
                    <th scope="row">Status</th>
                    <td>
                        <label for="status{{ $blogCategory->id }}" class="form-label"><strong></strong></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox"
                                role="switch" id="status{{ $blogCategory->id }}" name="status"
                                data-id="{{ $blogCategory->id }}" value="1"
                                {{ $blogCategory->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status{{ $blogCategory->id }}"></label>
                        </div>
                    </td>
                </tr>


            </tbody>
        </table>
        <a href="{{ route('category.create') }}" class="btn me-2"
            style="background-color: red; color: white;margin-top: 15px;">
            <i class="fa-solid fa-floppy-disk"></i> Create
        </a>
        <form action="{{ route('category.destroy', $blogCategory->id) }}" method="POST" class="btn me-2"
            style=" color: white;margin-top: 15px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('category.edit', $blogCategory->id) }}" class="btn me-2"
            style="background-color: red; color: white;margin-top: 15px;">
            <i class="fa-solid fa-floppy-disk"></i> Edit
        </a>

        <a href="{{ route('category.index') }}" class="btn btn-primary mt-3">Back to List</a>
    </div>

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
                            fetch('{{ route('blogcategory.status') }}', {
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
