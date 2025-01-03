@extends('dashboard')
@section('title', 'Blog Category')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    {{ Breadcrumbs::render('category') }} </li>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">

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
                    <div class="col-md-12 ">
                        <div class="card mb-4">
                            <div class="card-header d-flex ">
                                <a class="btn btn-success btn-sm"href="{{ route('category.create') }}"
                                    id="createNewProduct">


                                    <i class="fa fa-plus"></i> Create

                                </a>
                                <!-- Reset Button -->
                                <button type="button" class="btn btn-danger" onClick="resetTable()"
                                    style="margin-left: 20px">
                                    <i class="fa fa-undo"></i> Reset
                                </button>

                                <!-- Reload Button -->
                                <button type="button" class="btn btn-warning" id="reloadTable" onclick="location.reload();"
                                    style="margin-left: 20px">
                                    <i class="fa fa-sync"></i> Reload
                                </button>

                                <div class="d-flex" style="margin-left: 30px">
                                    <select id="bulkAction" class="form-select me-2" style="width: auto;">
                                        <option value="" selected disabled>Bulk Action</option>
                                        <option value="toggle-status">Toggle Status</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                    <button class="btn btn-secondary" id="applyBulkAction">Apply</button>
                                </div>
                                <a href="/all-tweets-csv" class="btn btn-primary">Export as CSV</a>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    {!! $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap', 'width' => '100%']) !!}
                                </div>
                            </div>_______ for responsive table design
                            <!-- /.card-body -->
                            {{-- <div class="card-footer clearfix">
                                {{ $blogCategoriestable->links('pagination::bootstrap-5') }}
                            </div> --}}
                        </div> <!-- /.card -->
                    </div> <!-- /.col -->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->


        <script>
            console.log('DataTable script loaded');
            document.addEventListener('DOMContentLoaded', function() {
                // Handle toggle switch change event with event delegation
                document.querySelector('.card-body').addEventListener('change', function(e) {
                    if (e.target.classList.contains('form-check-input')) {
                        var toggle = e.target;
                        var blogId = toggle.dataset.id;
                        var newStatus = toggle.checked ? 1 : 0;

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
                                // AJAX request to update status
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
                                toggle.checked = !toggle.checked;
                            }
                        });
                    }
                });
            });
        </script>



        {{-- //SCRIPT FOR THE BULK ACTION OR SO --}}

        <script>
            $('#select-all').click(function() {
                $('input[name="selected_rows[]"]').prop('checked', this.checked);
            });
        </script>
        {{-- //the bulk action herer --}}
        <script>
            // Setup bulk actions for this specific page
            setupBulkActions({
                applyBulkAction: '#applyBulkAction',
                rowSelector: 'input[name="selected_rows[]"]',
                bulkAction: '#bulkAction',
                updateUrl: '/blog-category/bulk-update-status', // URL for update status
                deleteUrl: '/blog-category/bulk-delete' // URL for delete
            });
        </script>




    @endsection

    {{-- //for the yajra datattable scripts --}}
    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            console.log('DataTable script loaded');
        </script>
    @endpush
