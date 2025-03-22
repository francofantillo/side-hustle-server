@extends('admin.layouts')
@section('title', 'User Detail')

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-9">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User /</span> Detail</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-4 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('admin/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Products</span>
                    <h3 class="card-title mb-2">{{ $products ?? "0" }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('admin/assets/img/icons/unicons/chart.png') }}" alt="Credit Card" class="rounded" />
                        </div>
                    </div>
                    <span>Total Services</span>
                    <h3 class="card-title text-nowrap mb-1">{{ $services ?? "0" }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('admin/assets/img/icons/unicons/chart.png') }}" alt="Credit Card" class="rounded" />
                        </div>
                    </div>
                    <span>Total Events</span>
                    <h3 class="card-title text-nowrap mb-1">{{ $events ?? "" }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('admin/assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card" class="rounded" />
                        </div>
                    </div>
                    <span>Total Jobs</span>
                    <h3 class="card-title text-nowrap mb-1">{{ $jobs ?? "0.00" }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-bottom: 15px">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    @if($user->image != null)
                        <img src="{{ $user->image }}" style="border-radius: 50%" alt="user image" width="200" height="200">
                    @else
                        <img src="{{ asset('admin/assets/img/user-image.jpg') }}" style="radius: 50%" alt="user image" width="200" height="200">
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
                                <td>{{ $user->name }}</td>
                                <th>Email Address</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $user->phone }}</td>
                                <th>Rating</th>
                                <td>{{ $user->rating }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ $user->country }}</td>
                                <th>Zip Code</th>
                                <td>{{ $user->zip_code }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($shop != null)
        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-bold py-3 mb-4">Shop</h4>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Shop Name</th>
                                        <th>Location</th>
                                        <th>Zip Code</th>
                                        <th style="text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ $shop->name }}
                                        </td>
                                        <td>{{ $shop->location }}</td>
                                        <td>
                                            {{ $shop->zip_code }}
                                        </td>
                                        <td style="text-align:center;">
                                            <a href="{{ url('admin/user-wise-products/'.$shop->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                       
                                    </tr>
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body" style="text-align: center;">
                        @if($shop->image != null)
                            <img src="{{ $shop->image }}" style="border-radius: 50%" alt="shop image" width="200" height="200">
                        @else
                            <img src="{{ asset('admin/assets/img/elements/2.jpg') }}" style="radius: 50%" alt="shop image" width="200" height="200">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($subs != null)
        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-bold py-3 mb-4">Subscription</h4>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Subscription ID</th>
                                        <th>Customer ID</th>
                                        <th>Subscription Plan</th>
                                        <th style="text-align: center">Amount</th>
                                        <th style="text-align: center;">Subscription Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $subs->stripe_subscription_id }}</td>
                                        <td>{{ $subs->stripe_customer_id }}</td>
                                        <td>{{ $subs->plan_interval }}</td>
                                        <td style="text-align: center">${{ $subs->plan_amount }}</td>
                                        <td style="text-align: center;">{{ date('m-d-Y', strtotime($subs->plan_period_start ))}}</td>
                                    </tr>
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <h4 class="fw-bold py-3 mb-4">JOBS</h4>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Budget</th>
                            <th>Area Code</th>
                            <th>Job Date</th>
                            <th style="text-align: center;">Total Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($jobs_list as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->title ?? '' }} </td>
                                <td>
                                    ${{ $item->budget }}
                                </td>
                                <td> {{ $item->area_code ?? '' }} </td>
                                <td> {{ date('m-d-Y', strtotime($item->job_date)) }}</td>
                                <td style="text-align: center;"> {{ $item->total_hours }}</td>
                                <td> {{ $item->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No Jobs Found!!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{$jobs_list->links('pagination::bootstrap-4')}}
            
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <h4 class="fw-bold py-3 mb-4">EVENTS</h4>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th style="text-align: center">Payment Type</th>
                            <th>Event Date</th>
                            <th>Duration</th>
                            <th>Location</th>
                            <th>Purpose</th>
                            <th>Theme</th>
                            <th>Attractions</th>
                            {{-- <th>Status</th> --}}
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($events_list as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td >
                                    {{ $item->name }}
                                </td>
                                <td> ${{ $item->price ?? '' }} </td>
                                <td style="text-align: center">{{ $item->payment_type ?? '' }} </td>
                                <td>{{ date('m-d-Y', strtotime($item->date)) }}</td>
                                <td>{{ date('h:i A', strtotime($item->start_time)).' - '.date('h:i A', strtotime($item->end_time)) }}</td>
                                <td>{{ $item->location }}</td>
                                <td>{{ $item->purpose }}</td>
                                <td>{{ $item->theme }}</td>
                                <td>{{ $item->available_attractions }}</td>
                                {{-- <td>{{ $item->status ?? "Pending" }}</td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No Events Found!!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $events_list->links('pagination::bootstrap-4')}}
            
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
            <h4 class="fw-bold py-3 mb-4">Reviews</h4>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th >Owner</th>
                            <th>Title</th>
                            <th style="text-align: center">Rating</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($reviews as $item)
                            @php
                                if($item->model_name == "Job") {
                                    $title = App\Models\Job::where('id', $item->model_id)->pluck('title')->first();
                                } elseif($item->model_name == "Event") {
                                    $title = App\Models\Event::where('id', $item->model_id)->pluck('name')->first();
                                } else {
                                    $title = "Test Title";
                                }
                            @endphp
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td >
                                    <img src="{{ $item["owner"]->image }}" alt="" height="70" width="70" style="border-radius: 50%">&nbsp;&nbsp;&nbsp;&nbsp; {{ $item["owner"]->name }}
                                </td>
                                <td> {{ $title ?? '' }} </td>
                                <td style="text-align: center"> {{ $item->rating }}</td>
                                <td> {{ $item->review }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No Reviews Found!!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{$reviews->links('pagination::bootstrap-4')}}
              
            </div>
        </div>
    </div> 
</div>


@endsection

@section('js')
    {{-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            let userid = $('#userid').val();
            table      =  $('#workerDetailTable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: `{{url('admin/worker-detail')}}/`+userid,
                            },
                            columns: [                
                                {data: 'title', name: 'title'},
                                {data: 'contractor', name: 'contractor'},
                                {data: 'date', name: 'date'},
                                {data: 'hourly_rate', name: 'hourly_rate'},
                                {data: 'job_type', name: 'job_type'},
                                {data: 'worker_type', name: 'worker_type'}, 
                                {data: 'location', name: 'location'}, 
                                {data: 'status', name: 'status'},                         
                            ]
            });  
        });
    </script> --}}
@endsection

