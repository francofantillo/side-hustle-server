@extends('admin.layouts')
@section('title', 'Delete Account Requests')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-9">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Delete Account Requests /</span> Listing</h4>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $item)
                            <tr>
                                <td><img src="{{ $item->image }}" width="80px" height="80px" style="border-radius: 50%" alt=""></td>
                                <td>{{ $item->name }}</td>
                                <td><a href="{{ url('admin/delete-account/'.$item->id) }}" class="btn btn-danger">Delete Account</a></td>
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
