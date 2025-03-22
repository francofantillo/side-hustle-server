@extends('admin.layouts')
@section('title', 'Events')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-9">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Events /</span> Listing</h4>
            </div>
        </div>
        <div class="row d-flex justify-content-end">
            <div class="col-md-4 mb-4">
                <select class="form-control d-inline" name="status" id="event_status" style="width:68%">
                    <option value="">Select Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="ongoing">Ongoing</option>
                </select>
                <button class="btn btn-primary d-inline event_filter" style="width:28%">Filter</button>
            </div>
        </div>
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="event_list">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Payment Type</th>
                            <th>Location</th>
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
                    d.status = $("#event_status").val();
                }
                let columns = [
                    { 
                        data: 'image',
                        name: 'image',
                        orderable : false,
                        render : function (data, type, row) {
                            if (row.event_images.length > 0) {
                                return `<img src="${row.event_images[0].image}" style="width : 70px; height: 70px" alt="user image">`;
                            } else {
                                return `<img src="{{ asset('admin/assets/img/user-image.jpg') }}" style="width : 70px; height: 70px" alt="user image">`;
                            }
                        }
                    },
                    { data: 'name', name: 'name', orderable : false },
                    { data: 'price', name: 'price', orderable : false },
                    { data: 'payment_type', name: 'payment_type', orderable : false },
                    { data: 'location', name: 'location', orderable : false },
                    { data: 'status', name: 'status', orderable : false },
                    {
                        data: 'id',
                        name : 'id',
                        render : function (data, type, row) {
                            let url = "{{ url('admin/event-detail/') }}";
                            let completeUrl = url + '/' + data;
                            return `<a href="${completeUrl}"><i class="fa fa-eye"></i></a>`
                        }
                    },
                ]
                customDatatable(
                    '#event_list',
                    "{{route('admin.events')}}",
                    "GET",
                    data,
                    columns,
                    'events'
                )
            }
            $(document).on('click', ".event_filter", function () {
                $("#event_list").DataTable().destroy();
                jobDatatable()
            })
        })
    </script>
@endsection
