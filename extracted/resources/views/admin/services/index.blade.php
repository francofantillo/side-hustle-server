@extends('admin.layouts')
@section('title', 'Services')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-9">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Services /</span> Listing</h4>
            </div>
        </div>
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="service_list">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Hourly Rate</th>
                            <th>Location</th>
                            <th>Zip Code</th>
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
                    d.type = 'Service'
                }
                let columns = [
                    { 
                        data: 'image',
                        name: 'image',
                        orderable : false,
                        render : function (data, type, row) {
                            if (row.product_images.length > 0) {
                                return `<img src="${row.product_images[0].image}" style="width : 70px; height: 70px" alt="user image">`;
                            } else {
                                return `<img src="{{ asset('admin/assets/img/user-image.jpg') }}" style="width : 70px; height: 70px" alt="user image">`;
                            }
                        }
                    },
                    { data: 'name', name: 'name', orderable : false },
                    { data: 'hourly_rate', name: 'hourly_rate', orderable : false },
                    { data: 'location', name: 'location', orderable : false },
                    { data: 'zip_code', name: 'zip_code', orderable : false },
                    {
                        data: 'id',
                        name : 'id',
                        render : function (data, type, row) {
                            let url = "{{ url('admin/service-detail/') }}";
                            let completeUrl = url + '/' + data;
                            return `<a href="${completeUrl}"><i class="fa fa-eye"></i></a>`
                        }
                    },
                ]
                customDatatable(
                    '#service_list',
                    "{{route('admin.products')}}",
                    "GET",
                    data,
                    columns,
                    'services'
                )
            }
        })
    </script>
@endsection
