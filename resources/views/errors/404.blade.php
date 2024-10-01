<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE v4 | Dashboard">
    <meta name="author" content="ColorlibHQ">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


</head> <!--end::Head--> <!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="wrapper d-flex justify-content-center align-items-center vh-100">

        <div class="content-wrapper text-center">
            <!-- Main content -->
            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-warning"> 404</h2>

                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
                        <p>
                            We could not find the page you were looking for.
                            Meanwhile, you may <a href="{{ route('dashboard') }}">return to dashboard</a>
                        </p>
                    </div>
                    <!-- /.error-content -->
                </div>
                <!-- /.error-page -->
            </section>
            <!-- /.content -->
        </div>

    </div>
</body>



</body>

</html>
