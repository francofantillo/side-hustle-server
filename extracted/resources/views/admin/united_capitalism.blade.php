@extends('admin.layouts')
@section('title')
United Capitalism
@endsection
@section('css')
<style>
    #cke_privacy_policy {
        width: 97% !important;
    }

    .cke_reset {
        width: 100% !important;
        height: auto !important;
    }

</style>
@endsection

@section('content')

<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">United Capitalism</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">

        <!-- Basic with Icons -->
        <div class="col-md-12 col-sm-12">
            <div class="card mb-4">

                <div class="card-body">
                    <form method="post" action="{{ route('admin.united_capitalism') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class=" form-label" for="basic-icon-default-message">Content</label>
                                <div class="input-group input-group-merge">
                                    {{-- <textarea class="form-control" id="privacy_policy" cols="80" rows="10">
                                </textarea> --}}
                                    <textarea name="united_capitalism" id="" cols="120" rows="10"
                                        id="united_capitalism">
                                    {{ $content->united_capitalism ?? ''}}
                                </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Update United Capitalism</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('united_capitalism');

</script>
@endsection
