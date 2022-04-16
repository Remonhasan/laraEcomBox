@extends('admin-layouts.master')

@section('styles')
@endsection

@section('admin_content')

<div class="card shadow">
    <div class="row">
        <div class="col-md-12">
            <form>
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="First name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Last name">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

@endsection