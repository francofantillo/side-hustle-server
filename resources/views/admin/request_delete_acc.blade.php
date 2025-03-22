@extends('admin.layouts')
@section('title', 'Delete Account Request')

@section('css')

@endsection
@section('content')
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">   
    <form action="{{ route('admin.accRequest')}}">
        @csrf
        <button>Delete My Account</button>
    </form>
</div>
<!-- / Content -->
@endsection

@section('js')
<script>


</script>
@endsection
