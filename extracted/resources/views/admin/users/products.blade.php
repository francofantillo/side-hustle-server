@extends('admin.layouts')
@section('title', 'Products')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-9">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Products /</span> Listing</h4>
            </div>
        </div>
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price/Hourly Rate</th>
                            <th>Delivery/Serive Type</th>
                            <th>Location</th>
                            <th>Zip Code</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($products as $item)
                            <tr>
                                @if(count($item["product_images"]) > 0)
                                    <td>
                                        <img style="border-radius: 10px;"width="60px" height="60px" src="{{ asset($item["product_images"][0]->image) }}" />
                                    </td>
                                @else
                                    <td>
                                    </td>
                                @endif
                             
                                <td>{{ $item->name}}</td>
                                <td>{{ $item->type}}</td>
                                @if($item->type == "Product")
                                    <td>${{ $item->price}}</td>
                                    <td>{{ $item->delivery_type}}</td>
                                @else
                                    <td>{{ $item->hourly_rate }}</td>
                                    <td>{{ $item->service_type}}</td>
                                @endif
                                <td>{{ $item->location}}</td>
                                <td>{{ $item->zip_code}}</td>
                                <td>{{ $item->description}}</td>
                            </tr>
                        @empty
                            
                        @endforelse
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
@endsection
