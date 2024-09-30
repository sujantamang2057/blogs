@extends('dashboard')

@section('content')
    <div class="app-content">
        <span class="breadcrumbs">
            {{ Breadcrumbs::render('blog') }}
        </span>
        <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row"> <!--begin::Col-->
                @php $counter = 1; @endphp
                @foreach ($statusCounts as $modelName => $counts)
                    <div class="col-lg-3 col-6"> <!--begin::Small Box Widget-->
                        <div class="small-box {{ $counter % 2 == 0 ? 'text-bg-primary' : 'text-bg-warning' }}">
                            <h1>{{ $modelName }}</h1>
                            <div class="inner">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Active</p>
                                        <h3>{{ $counts['active'] }}</h3>
                                    </div>
                                    <div class="col-6">
                                        <p>Inactive</p>
                                        <h3>{{ $counts['inactive'] }}</h3>
                                    </div>
                                </div>
                            </div>


                        </div> <!--end::Small Box Widget-->
                    </div> <!--end::Col-->
                    @php $counter++; @endphp
                @endforeach
                <!--end::Row--> <!--begin::Row-->

            </div> <!--end::Container-->
        </div>

        <style>
            .breadcrumbs {
                margin-top: 10px;
                margin-bottom: 0;
                text-align: left
            }
        </style>
    @endsection
