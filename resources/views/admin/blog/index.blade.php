@extends('dashboard')

@section('title', 'Blog post')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Blog post</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog post
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
                                <h3 class="card-title">Blog post List</h3>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                                    <a class="btn btn-success btn-sm" href="{{ route('blog.create') }}"
                                        id="createNewProduct">
                                        <i class="fa fa-plus"></i> Create New Blog post
                                    </a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px">No</th>
                                            <th style="width: 200px">Action</th>
                                            <th>Title</th>
                                            {{-- <th>Description</th> --}}
                                            <th>Blog Category</th>
                                            <th style="width: 100px">Image</th>

                                            <th>Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($blogs as $blog)
                                            <tr class="align-middle">
                                                <td>{{ $loop->iteration }}</td>

                                                <td>
                                                    <a href="{{ route('blog.show', $blog->id) }}"
                                                        class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('blog.edit', $blog->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('blog.destroy', $blog->id) }}" method="POST"
                                                        style="display:inline;" id="deleteForm-blog-{{ $blog->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="handleDelete('deleteForm-blog-{{ $blog->id }}')">
                                                            <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                </td>





                                                <td>{{ $blog->title }}</td>
                                                {{-- <td>{!! $blog->description !!}</td> --}}





                                                <td>

                                                    @if ($blog->blogCategory)
                                                        {{ $blog->blogCategory->title }}
                                                    @else
                                                        None
                                                    @endif
                                                </td>
                                                {{-- //for teh botton --}}
                                                <td>
                                                    @if ($blog->image)
                                                        <a href="{{ asset('storage/' . $blog->image) }}"
                                                            data-fancybox="gallery" data-caption="{{ $blog->title }}">
                                                            <img src="{{ asset('storage/images/resized/' . basename($blog->image)) }}"
                                                                alt="{{ $blog->title }}"
                                                                style="width: 50px; height: auto;">
                                                        </a>
                                                    @else
                                                        <p>No image available</p>
                                                    @endif
                                                </td>

                                                {{-- //button end --}}
                                                <td>
                                                    <label for="status{{ $blog->id }}"
                                                        class="form-label"><strong></strong></label>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input @error('status') is-invalid @enderror"
                                                            type="checkbox" role="switch" id="status{{ $blog->id }}"
                                                            name="status" data-id="{{ $blog->id }}" value="1"
                                                            {{ $blog->status ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="status{{ $blog->id }}"></label>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">

                            </div>
                            <div class="card-footer clearfix">
                                {{ $blogs->links('pagination::bootstrap-5') }}
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
