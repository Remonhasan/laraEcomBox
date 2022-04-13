@extends('admin-layouts.master')
@section('admin_content')

<h5 class="card-title text-primary">Congratulations {{ Auth::guard('admin')->user()->name }} 🎉
</h5>
@endsection