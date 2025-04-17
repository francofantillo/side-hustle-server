@extends('admin.layouts')
@section('title', 'Add Banner')

@section('css')
<style>
    .row {
        margin-bottom: 20px;
    }
    #bannerImage {
        display: none;
        width:250px;
        height:210px;
    }
</style>
@endsection
@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-9">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Add Banner</span></h4>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="card-body">
           <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <div class="label">Banner Name</div>
                        <input type="text" name="name" class="form-control" maxlength="25">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <div class="label">Banner Description</div>
                        <input type="text" name="description" class="form-control" maxlength="80">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <div class="label">Zip Code</div>
                        <input type="text" name="zip_code" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="label">Banner Image</div>
                        <input type="file" name="image" onchange="readURL(this);" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="" id="bannerImage" style=""  alt="Banner Image">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                       <button class="btn btn-primary">Add Banner</button>
                    </div>
                </div>
            </div>
           </form>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#bannerImage').attr('src', e.target.result).width(150).height(200);
                    $('#bannerImage').show();
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection

