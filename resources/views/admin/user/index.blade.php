@extends('dashboard')

@section('title', 'User')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">
                            User
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
                                <h3 class="card-title">User</h3>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">

                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px">No</th>
                                            <th style="width: 280px">Action</th>

                                            <th>Name</th>
                                            <th>EmaiL</th>




                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $user)
                                            <tr class="align-middle">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('user.show', $user->id) }}"
                                                        class="btn btn-info btn-sm"> <i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('user.edit', $user->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a>
                                                    {{-- to make the sign in user not be able to edelete itself can only delete other --}}
                                                    @if ($user->id !== Auth::id())
                                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                            style="display:inline;"
                                                            id="deleteForm-user-{{ $user->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="handleDelete('deleteForm-user-{{ $user->id }}')"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    @endif


                                                </td>





                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>








                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">

                            </div>
                            <div class="card-footer clearfix">
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
