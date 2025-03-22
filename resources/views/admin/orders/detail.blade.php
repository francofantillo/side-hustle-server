@extends('admin.layouts')
@section('title', 'Order Detail')

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-9">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Order /</span> Detail</h4>
        </div>
    </div>

    <div class="row" style="margin-bottom: 15px">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    @if($order->user->image != null)
                        <img src="{{ $order->user->image }}" style="border-radius: 50%" alt="user image" width="200" height="200">
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
                        <h4>Customer Detail</h4>
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <td>{{ $order->customer_name }}</td>
                                <th>Email Address</th>
                                <td>{{ $order->customer_email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $order->user->phone }}</td>
                                <th>Zip Code</th>
                                <td>{{ $order->user->zip_code }}</td>
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
                    <h4 class="fw-bold">Orders</h4>
                </div>
                <div class="col-md-4">
                    <select class="form-control d-inline" name="order_type" id="order_type" style="width:68%">
                        <option value="">Select Type</option>
                        <option value="product">Products</option>
                        <option value="service">Services</option>
                    </select>
                    <button class="btn btn-primary d-inline order_type_filter" style="width:28%">Filter</button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="orders_list">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Product Per Price</th>
                            <th>Product Quantity</th>
                            <th>Total</th>
                            <th>Order Date</th>
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
            orderList()
            function orderList() {
                let id = window.location.pathname.split("/").pop()
                let data = function (d) {
                    d.id = id
                    d.type = $("#order_type").val()
                }
                let columns = [
                    { 
                        data: 'product_image',
                        name: 'product_image',
                        orderable : false,
                        render : function (data, type, row) {
                            if (row.product_image != null) {
                                return `<img src="${row.product_image}" style="height:70px;width:70px" alt="user image">`;
                            } else {
                                return `<img src="{{ asset('admin/assets/img/user-image.jpg') }}" style="width : 70px; height: 70px" alt="user image">`;
                            }
                        }
                    },
                    { data: 'product_name', name: 'product_name', orderable : false },
                    { data: 'type', name: 'type', orderable : false },
                    { data: 'product_per_price', name: 'product_per_price', orderable : false },
                    { data: 'product_qty', name: 'product_qty', orderable : false },
                    { data: 'product_subtotal_price', name: 'product_subtotal_price', orderable : false },
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
                ]
                let url = "{{ url('admin/order-detail/') }}";
                let completeUrl = url + '/' + id;
                customDatatable(
                    '#orders_list',
                    completeUrl,
                    "GET",
                    data,
                    columns,
                    'orders'
                )
            }
            $(document).on('click', '.order_type_filter', function () {
                $("#orders_list").DataTable().destroy();
                orderList()
            })
        })
    </script>
@endsection

