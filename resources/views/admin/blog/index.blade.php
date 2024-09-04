@extends('dashboard')

@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Blog post</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
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
            @if (Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            </div>    
            @endif      
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
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Blog Category</th>

                                        <th>Status</th>
                                        <th style="width: 280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($blog as $blog)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if ($blog->image)
                                                    <img src="{{ asset('storage/' . $blog->image) }}"
                                                        alt="{{ $blog->title }}" style="width: 50px; height: auto;">
                                                @else
                                                    <p>No image available</p>
                                                @endif
                                            </td>



                                            <td>{{ $blog->title }}</td>
                                            <td>{!! $blog->description !!}</td>
                                            <td>                                       
                                                @if ($blog->blogCategory)
                                                    {{ $blog->blogCategory->title }}
                                                @else
                                                    None
                                                @endif
                                            </td>
                                            <td>{{ $blog->status ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                <a href="{{ route('blog.show', $blog->id) }}"
                                                    class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('blog.edit', $blog->id) }}"
                                                    class="btn btn-primary btn-sm">Edit</a>
                                                <form action="{{ route('blog.destroy', $blog->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                                                </form>
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
                        </div>
                    </div> <!-- /.card -->
                </div> <!-- /.col -->
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content-->
@endsection
