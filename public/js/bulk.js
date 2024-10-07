// bulkActions.js
function setupBulkActions(selectors) {
    console.log('BULK LOADING');

    document.addEventListener('DOMContentLoaded', function () {
        // Apply Bulk Action
        $(selectors.applyBulkAction).click(function () {
            console.log('Apply Bulk Action clicked');
            var selectedRows = $(selectors.rowSelector + ':checked').map(function () {
                return $(this).val();
            }).get();
            var bulkAction = $(selectors.bulkAction).val();

            if (bulkAction && selectedRows.length > 0) {
                if (bulkAction === 'toggle-status') {
                    updateStatus(selectedRows, selectors.updateUrl);
                } else if (bulkAction === 'delete') {
                    deleteSelectedRows(selectedRows, selectors.deleteUrl);
                }
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select at least one row and select an action.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
}

function updateStatus(ids, url) {
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            ids: ids
        },
        success: function (response) {
            Swal.fire({
                title: 'Success!',
                text: 'Status updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        },
        error: function (error) {
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

function deleteSelectedRows(ids, url) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids
                },
                success: function (response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Rows deleted successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                },
                error: function (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while deleting rows.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}
