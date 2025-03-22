@extends('admin.layouts')
@section('title', 'Dashboard')

@section('css')

@endsection
@section('content')
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/chart-success.png') }}"
                                alt="chart success" class="rounded" /> --}}
                                <i class="fa fa-user" style="font-size: 24px; color:darkcyan" aria-hidden="true"></i>

                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Users</span>
                    <h3 class="card-title mb-2">
                        {{ number_format($users) ?? "0"}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/chart.png') }}" alt="chart success"  class="rounded" /> --}}
                            <i class="fa fa-shopping-cart" style="font-size: 24px; color:rgb(19, 37, 58)" aria-hidden="true"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Orders</span>
                    <h3 class="card-title mb-2">
                        {{ number_format($orders) ?? "0" }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="fa fa-users rounded" style="font-size: 24px; color:rgb(10, 20, 20)"  aria-hidden="true"></i>
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/chart.png') }}" alt="chart success"  class="rounded" /> --}}
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Subscriptions</span>
                    <h3 class="card-title mb-2">
                        {{ number_format($subscriptions) ?? "0" }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/cc-primary.png') }}" alt="chart success"
                                class="rounded" /> --}}
                                <i class="fa fa-credit-card  rounded"  style="font-size: 24px; color:rgb(7, 66, 66)" aria-hidden="true"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Earnings</span>
                    <h3 class="card-title mb-2">
                        ${{ number_format($earnings) ?? "0" }}
                    </h3>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Job Statistics</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <h2 class="mb-2">
                                {{ number_format($total_jobs) ?? ""}}
                            </h2>
                            <span>Total Jobs</span>
                        </div>
                        <div id="orderStatisticsChart"></div>
                    </div>
                    <ul class="p-0 m-0">
                        <a href="{{ url('admin/jobs?job_status=Pending')}}">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class='bx bx-hive'></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">

                                    <div class="me-2">
                                        <h6 class="mb-0">Pending</h6>
                                        <small class="text-muted">Pending jobs</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">
                                            {{ number_format($pending_jobs) ?? ""}}
                                        </small>
                                    </div>
                                </div>

                            </li>
                        </a>
                        <a href="{{ url('admin/jobs?job_status=Ongoing')}}">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class='bx bx-doughnut-chart'></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Ongoing</h6>
                                        <small class="text-muted">Ongoing jobs</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">
                                            {{ number_format($ongoing_jobs) ?? "" }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        </a>
                        <a href="{{ url('admin/jobs?job_status=Completed')}}">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Completed</h6>
                                        <small class="text-muted">Completed Jobs</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">
                                            {{ number_format($completed_jobs) ?? ""}}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        </a>
                    </ul>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="fa fa-calendar alert-success" style="font-size: 24px; color: #090a09" aria-hidden="true"></i>
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/chart.png') }}" alt="chart success"
                                class="rounded" /> --}}
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Events</span>
                    <h3 class="card-title mb-2">
                        {{ number_format($events) ?? "0" }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card" class="rounded" /> --}}
                            <i class="fa fa-building rounded"   style="font-size: 24px; color:rgb(36, 40, 92)" aria-hidden="true"></i>
                        </div>
                    </div>
                    <span>Jobs</span>
                    <h3 class="card-title text-nowrap mb-1">
                        {{ number_format($jobs) ?? "0"}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" /> --}}
                            <i class="fa fa-window-restore rounded" style="font-size: 24px; color:rgb(20, 43, 85)" aria-hidden="true"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Products</span>
                    <h3 class="card-title mb-2">
                        {{ number_format($products) ?? "0" }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            {{-- <img src="{{ asset('admin/assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card" class="rounded" /> --}}
                            <i class="fa fa-server rounded"  style="font-size: 24px; color:rgb(27, 95, 61)"  aria-hidden="true"></i>
                        </div>
                    </div>
                    <span>Services</span>
                    <h3 class="card-title text-nowrap mb-1">
                        {{ number_format($services) ?? "0"}}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
@endsection

@section('js')
<script>


</script>
@endsection
