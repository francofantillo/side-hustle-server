@extends('admin.layouts')
@section('title', 'Jobs')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-9">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Jobs /</span> Listing</h4>
            </div>
        </div>
        <div class="row d-flex justify-content-end">
            <div class="col-md-4 mb-4">
                <!-- <form> -->
                    <select class="form-control d-inline" name="status" id="job_status" style="width:68%">
                        <option value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <button class="btn btn-primary d-inline job_filter" style="width:28%">Filter</button>
                <!-- </form> -->
            </div>
        </div>
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="job_list">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Budget</th>
                            <th>Zip Code</th>
                            <th>Job Date</th>
                            <th>Job Start Time</th>
                            <th>Job End Time</th>
                            <th>Status</th>
                            <th>Action</th>
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
            jobDatatable()
            function jobDatatable() {
                let data = function (d) {
                    d.status = $("#job_status").val();
                }
                let columns = [
                    { 
                        data: 'image',
                        name: 'image',
                        orderable : false,
                        render : function (data, type, row) {
                            if (row.images.length > 0) {
                                return `<img src="${row.images[0].image}" style="width : 70px; height: 70px" alt="user image">`;
                            } else {
                                return `<img src="{{ asset('admin/assets/img/user-image.jpg') }}" style="width : 70px; height: 70px" alt="user image">`;
                            }
                        }
                    },
                    { data: 'title', name: 'title', orderable : false },
                    {
                        data: 'budget',
                        name: 'budget',
                        orderable : false,
                        render : function (data, type, row) {
                            return `<td>$${data}</td>`
                        }
                    },
                    { data: 'area_code', name: 'area_code', orderable : false },
                    {
                        data: 'job_date',
                        name: 'job_date',
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
                    {
                        data: 'job_time',
                        name: 'job_time',
                        // orderable : false,
                        // render : function (data, type, row) {
                        //     const date = new Date(data);
                        //     let yyyy = date.getFullYear();
                        //     let mm = date.getMonth() + 1;
                        //     let dd = date.getDate();

                        //     if (dd < 10) dd = '0' + dd;
                        //     if (mm < 10) mm = '0' + mm;

                        //     const formatted = mm + '-' + dd + '-' + yyyy;
                        //     return `<td>${formatted}</td>`
                        // }
                    },
                    {
                        data: 'end_time',
                        name: 'end_time',
                    },
                    // { data: 'location', name: 'location', orderable : false },
                    { 
                        data: 'status',
                        name: 'status',
                        orderable : false,
                    },
                    {
                        data: 'id',
                        name : 'id',
                        render : function (data, type, row) {
                            let url = "{{ url('admin/job-detail/') }}";
                            let completeUrl = url + '/' + data;
                            return `<a href="${completeUrl}"><i class="fa fa-eye"></i></a>`
                        }
                    },
                ]
                customDatatable(
                    '#job_list',
                    "{{route('admin.userJobs')}}",
                    "GET",
                    data,
                    columns,
                    'jobs'
                )
            }
            $(document).on('click', ".job_filter", function () {
                $("#job_list").DataTable().destroy();
                jobDatatable()
            })
        })
    </script>
@endsection
