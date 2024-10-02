@if (session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            // Clear the session message after displaying it
            window.location.reload(); // Refresh the page to clear the session message
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error', // Use 'error' icon
            confirmButtonText: 'OK',
            showCancelButton: false, // Optional: hide cancel button
            backdrop: true // Optional: enable backdrop
        }).then((result) => {
            // Refresh the page to clear the session message
            window.location.reload(); // Refresh the page to clear the session message
        });
    </script>
@endif





@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </ul>
    </div>
@endif
