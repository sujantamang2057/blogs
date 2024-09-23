@extends('dashboard')
@section('title', 'Blog Category')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Blog Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog Category
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                @include('admin.message.alert')
                <!--begin::Row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Blog Category List</h3>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                                    <a class="btn btn-success btn-sm" href="{{ route('category.create') }}"
                                        id="createNewProduct">
                                        <i class="fa fa-plus"></i> Create New Blog Category
                                    </a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px">No</th>
                                            <th style="width: 150px">Action</th>
                                            <th style="width: 200px">Title</th>
                                            {{-- <th>Slug</th> --}}
                                            <th>Parent Category</th>
                                            <th style="width: 100px">Image</th>

                                            <th style="width: 100px">Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($blogCategoriestable as $blogCategories)
                                            <tr class="align-middle">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('category.show', $blogCategories->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> <!-- View Icon -->
                                                    </a>
                                                    <a href="{{ route('category.edit', $blogCategories->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i> <!-- Edit Icon -->
                                                    </a>
                                                    <form id="deleteForm-blog-{{ $blogCategories->id }}"
                                                        action="{{ route('category.destroy', $blogCategories->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="handleDelete('deleteForm-blog-{{ $blogCategories->id }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>





                                                <td>{{ $blogCategories->title }}</td>
                                                {{-- <td>{{ $blogCategories->slug }}</td> --}}
                                                <td>
                                                    @if ($blogCategories->ParentBlogCategory)
                                                        {{ $blogCategories->ParentBlogCategory->title }}
                                                    @else
                                                        None
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($blogCategories->image)
                                                        <a href="{{ asset('storage/' . $blogCategories->image) }}"
                                                            data-fancybox="gallery"
                                                            data-caption="{{ $blogCategories->title }}">
                                                            <img src="{{ asset('storage/images/resized/' . basename($blogCategories->image)) }}"
                                                                alt="{{ $blogCategories->title }}"
                                                                style="width: 50px; height: auto;">
                                                        </a>
                                                    @else
                                                        <p>No image available</p>
                                                    @endif
                                                </td>

                                                <td>
                                                    <label for="status{{ $blogCategories->id }}"
                                                        class="form-label"><strong></strong></label>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input @error('status') is-invalid @enderror"
                                                            type="checkbox" role="switch"
                                                            id="status{{ $blogCategories->id }}" name="status"
                                                            data-id="{{ $blogCategories->id }}" value="1"
                                                            {{ $blogCategories->status ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="status{{ $blogCategories->id }}"></label>
                                                    </div>
                                                </td>
                                                {{-- //FOR THR ACTION --}}


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {{ $blogCategoriestable->links('pagination::bootstrap-5') }}
                            </div>
                        </div> <!-- /.card -->
                    </div> <!-- /.col -->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->

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
