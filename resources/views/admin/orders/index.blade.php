@extends('admin.layouts')
@section('title', 'Orders')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-9">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Orders /</span> Listing</h4>
            </div>
        </div>
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="order_list">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Total Items</th>
                            <th>Total</th>
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
            let data = function (d) {
                d.type = 'Product'
            }
            let columns = [
                { data: 'order_no', name: 'order_no', orderable : false },
                { data: 'customer_name', name: 'customer_name', orderable : false },
                { data: 'customer_email', name: 'customer_email', orderable : false },
                { data: 'items_total', name: 'items_total', orderable : false },
                { data: 'total', name: 'total', orderable : false },
                {
                    data: 'id',
                    name : 'id',
                    render : function (data, type, row) {
                        let url = "{{ url('admin/order-detail/') }}";
                        let completeUrl = url + '/' + data;
                        return `<a href="${completeUrl}"><i class="fa fa-eye"></i></a>`
                    }
                },
            ]
            customDatatable(
                '#order_list',
                "{{route('admin.allOrders')}}",
                "GET",
                data,
                columns,
                'orders'
            )
        })
    </script>
@endsection
