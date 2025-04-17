@extends('admin.layouts')
@section('title', 'Job Detail')

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-9">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Job /</span> Detail</h4>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <h4 class="fw-bold py-3 mb-4">JOB DETAIL</h4>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Total Hours</th>
                            <td>{{ $job->total_hours ?? "" }}</td>
                            <th>Location</th>
                            <td>{{ $job->location ?? "" }}</td>
                        </tr>
                        @if($job["assign_user"] != null)
                            <tr>
                                <th>Assigned User</th>
                                <td>{{$job->assign_user->name ?? ""}}</td>
                                <th>Bid Amount</th>
                                <td>${{ $job->bid_amount ?? ""}}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Description</th>
                            <td>{{ $job->description ?? "" }}</td>
                            <th>Additional Information</th>
                            <td>{{ $job->additional_information ?? "" }}</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="fw-bold py-3 mb-4">JOB CREATED BY</h4>
            <div class="row" style="margin-bottom: 15px">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body" style="text-align: center;">
                            @if($job->user->image != null)
                                <img src="{{ $job->user->image }}" style="border-radius: 50%" alt="user image" width="200" height="200">
                            @else
                                <img src="{{ asset('admin/assets/img/user-image.jpg') }}" alt="user image" width="200" height="200">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9" >
                    <div class="card" style="height: 250px">
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $job->user->name }}</td>
                                        <th>Email Address</th>
                                        <td>{{ $job->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $job->user->phone }}</td>
                                        <th>Rating</th>
                                        <td>{{ $job->user->rating }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>{{ $job->user->country }}</td>
                                        <th>Zip Code</th>
                                        <td>{{ $job->user->zip_code }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <h4 class="fw-bold py-3 mb-4">JOB REQUEST</h4>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="job_request">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Bid Amount</th>
                            <th>Job Request Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <h4 class="fw-bold py-3 mb-4">JOB REVIEWS</h4>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="job_review">
                    <thead>
                        <tr>
                            <th>Giver</th>
                            <th>Owner</th>
                            <th>Rating</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            let data = function (d) {
                d.id = window.location.pathname.split("/").pop()
            }
            let jobRequestColumns = [
                { 
                    data: 'name', 
                    name: 'name', 
                    orderable : false,
                    render : function (data, type, row) {
                        return row.applier.name;
                    }
                },
                { data: 'bid_amount', name: 'bid_amount', orderable : false },
                {
                    data: 'created_at',
                    name: 'created_at',
                    orderable : false,
                    render : function (data, type, row) {
                        const date = new Date(data);
                        let yyyy = date.getFullYear();
                        let mm = date.getMonth() + 1;
                        let dd = date.getDate();

                        if (dd < 10) dd = '0' + dd;
                        if (mm < 10) mm = '0' + mm;

                        const formatted = mm + '-' + dd + '-' + yyyy;
                        return `<td>${formatted}</td>`
                    }
                },
                { data: 'status', name: 'status', orderable : false },
            ]
            customDatatable(
                '#job_request',
                "{{route('admin.jobRequest')}}",
                "GET",
                data,
                jobRequestColumns,
                'Job Request'
            )

            let jobReviewsColumns = [
                { 
                    data: 'giver', 
                    name: 'giver', 
                    orderable : false,
                    render : function (data, type, row) {
                        return row.owner.name;
                    }
                },
                { 
                    data: 'owner', 
                    name: 'owner', 
                    orderable : false,
                    render : function (data, type, row) {
                        return row.user.name;
                    }
                },
                { data: 'rating', name: 'rating', orderable : false },
                { data: 'review', name: 'review', orderable : false },
            ]
            customDatatable(
                '#job_review',
                "{{route('admin.jobReviews')}}",
                "GET",
                data,
                jobReviewsColumns,
                'Job Reviews'
            )
        })
    </script>
@endsection

