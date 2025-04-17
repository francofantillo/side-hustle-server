@extends('admin.layouts')
@section('title', 'Users')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-9">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Listing</h4>
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
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Zip Code</th>
                            <th>Country</th>
                            <th>Rating</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($users as $item)
                            <tr>
                                @if($item->image == null)
                                    <td>
                                        <img style="border-radius: 10px;"width="60px" height="60px" src="{{ asset('admin/assets/img/user-image.jpg') }}" />
                                    </td>
                                @else
                                    <td>
                                        <img style="border-radius: 10px;"width="60px" height="60px" src="{{ asset($item->image) }}" />
                                    </td>
                                @endif
                             
                                <td>{{ $item->name}}</td>
                                <td>{{ $item->email}}</td>
                                <td>{{ $item->phone}}</td>
                                <td>{{ $item->zip_code}}</td>
                                <td>{{ $item->country}}</td>
                                <td>{{ $item->rating}}</td>
                                <td>
                                    <a href="{{ url('admin/user-detail/'.$item->id)}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
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
    {{-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            table =  $('#workersTable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: "{{ url('admin/workers') }}",
                            columns: [                
                                {data: 'image', name: 'image'},
                                {data: 'name', name: 'name'},
                                {data: 'email', name: 'email'},
                                {data: 'phone', name: 'phone'},
                                {data: 'service_type', name: 'service_type'},
                                {data: 'hourly_rate', name: 'hourly_rate'},
                                {data: 'rating', name: 'rating'},
                                {data: 'gender', name: 'gender'},
                                {data: 'action', name: 'action'},                           
                            ]
            });  
        });
    </script> --}}
@endsection
