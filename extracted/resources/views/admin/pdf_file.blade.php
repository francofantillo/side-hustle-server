@extends('admin.layouts')
@section('title')
Upload File
@endsection

@section('content')
  
   <!-- Content -->

     <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">File Upload</h4>

        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
        
          <!-- Basic with Icons -->
          <div class="col-md-12 col-sm-12">
            <div class="card mb-4">
          
              <div class="card-body">
                    @if(count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                <form method="post" enctype="multipart/form-data" action="{{ route('admin.pdf_file') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class=" form-label" for="basic-icon-default-message">Select PDF File</label>
                            <div class="input-group input-group-merge">
                               <input type="file" name="file" accept="application/pdf">
                            </div>
                        </div>
                    </div>
                  <div class="row">
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </div>
                </form>
                <div class="col-sm-2 mt-4">
                  @if (App\Models\Setting::find(1)->pdf_file != null)
                    <a href="{{route('admin.pdf')}}" target="_blank">View File</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- / Content -->
@endsection

@section('js')
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
@endsection
