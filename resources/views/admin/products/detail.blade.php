@extends('admin.layouts')
@section('title', 'Product Detail')

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-9">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Product /</span> Detail</h4>
        </div>
    </div>

    <div class="row" style="margin-bottom: 15px">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    @if($product->product_owner->image != null)
                        <img src="{{ $product->product_owner->image }}" style="border-radius: 50%" alt="user image" width="200" height="200">
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
                        <h4>Owner Detail</h4>
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <td>{{ $product->product_owner->name }}</td>
                                <th>Email Address</th>
                                <td>{{ $product->product_owner->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $product->product_owner->phone }}</td>
                                <th>Zip Code</th>
                                <td>{{ $product->product_owner->zip_code }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 15px">
        <div class="col-md-12" >
            <div class="card" style="height: 250px">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <h4>Product Detail</h4>
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <td>{{ $product->name }}</td>
                                <th>Location</th>
                                <td>{{ $product->location }}</td>
                            </tr>
                            <tr>
                                <th>Delivery Type</th>
                                <td>{{ $product->delivery_type }}</td>
                                <th>Zip Code</th>
                                <td>{{ $product->zip_code }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $product->description }}</td>
                                <th>Additional Information</th>
                                <td>{{ $product->additional_information }}</td>
                            </tr>
                            <tr>
                                <th>Total Orders</th>
                                <td>{{ count($product->orders) }}</td>
                                <th></th>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <div class="col-md-8 mb-4 d-flex">
                <h4 class="fw-bold">Orders</h4>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="orders_list">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Product Quantity</th>
                            <th>Total</th>
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
            let columns = [
                { 
                    data: 'customer_name',
                    name: 'customer_name',
                    orderable : false
                },
                { 
                    data: 'customer_email',
                    name: 'customer_email',
                    orderable : false
                },
                { 
                    data: 'product_qty',
                    name: 'product_qty',
                    orderable : false,
                    render : function (data, type, row) {
                        return row.order_details[0]?.product_qty
                    }
                },
                { 
                    data: 'product_subtotal_price',
                    name: 'product_subtotal_price',
                    orderable : false,
                    render : function (data, type, row) {
                        return row.order_details[0]?.product_subtotal_price
                    }
                },
            ]
            customDatatable(
                '#orders_list',
                "{{route('admin.productOrders')}}",
                "GET",
                data,
                columns,
                'orders'
            )
        })
    </script>
@endsection

