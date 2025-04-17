@extends('admin.layouts')
@section('title', 'Event Detail')

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-9">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Event /</span> Detail</h4>
        </div>
    </div>

    <div class="row" style="margin-bottom: 15px">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    @if($event->event_owner->image != null)
                        <img src="{{ $event->event_owner->image }}" style="border-radius: 50%" alt="user image" width="200" height="200">
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
                                <td>{{ $event->event_owner->name }}</td>
                                <th>Email Address</th>
                                <td>{{ $event->event_owner->email }}</td>
                            </tr>
                            <tr>
                                <th>Event Name</th>
                                <td>{{ $event->name }}</td>
                                <th>Date</th>
                                <td>{{ date('m-d-Y', strtotime($event->date)) }}</td>
                            </tr>
                            <tr>
                                <th>Purpose</th>
                                <td>{{ $event->purpose }}</td>
                                <th>Theme</th>
                                <td>{{ $event->theme }}</td>
                            </tr>
                            <tr>
                                <th>Start Time</th>
                                <td>{{ $event->start_time }}</td>
                                <th>End Time</th>
                                <td>{{ $event->end_time }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-4 d-flex">
                    <h4 class="fw-bold">USERS</h4>
                </div>
                <div class="col-md-4">
                    <select class="form-control d-inline" name="status" id="event_users_status" style="width:68%">
                        <option value="">Select Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="ongoing">Ongoing</option>
                    </select>
                    <button class="btn btn-primary d-inline event_users_filter" style="width:28%">Filter</button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="event_users">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
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
            eventUsers()
            function eventUsers()
            {
                let data = function (d) {
                    d.id = window.location.pathname.split("/").pop()
                    d.status = $("#event_users_status").val()
                }
                let columns = [
                    { 
                        data: 'name',
                        name: 'name',
                        orderable : false,
                        render : function (data, type, row) {
                            return row.user.name
                        }
                    },
                    { 
                        data: 'email',
                        name: 'email',
                        orderable : false,
                        render : function (data, type, row) {
                            return row.user.email
                        }
                    },
                    { 
                        data: 'phone',
                        name: 'phone',
                        orderable : false,
                        render : function (data, type, row) {
                            return row.user.phone
                        }
                    },
                    { data: 'status', name: 'status', orderable : false },
                ]
                customDatatable(
                    '#event_users',
                    "{{route('admin.eventUsers')}}",
                    "GET",
                    data,
                    columns,
                    'event users'
                )
            }
            $(document).on('click', '.event_users_filter', function () {
                $("#event_users").DataTable().destroy();
                eventUsers()
            })
        })
    </script>
@endsection

