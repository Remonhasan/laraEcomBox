@extends('admin-layouts.master')

@section('styles')
@endsection

@section('admin_content')

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Categories</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">{{ __('Category') }}</li>
            <li class="breadcrumb-item active"><a href="{{ route('category.list') }}">{{ __('List') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Add') }}</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
               <div class="row">
                 <div class="col-md-6">
                  <i class="fas fa-table mt-2"></i>
                  {{ __('Edit Category') }}
                 </div>
                 <div class="col-md-6 d-flex justify-content-end">
                  <a href="{{ route('category.list') }}" type="button" class="btn btn-primary"><i class="fas fa-plus me-1"></i>{{ __('List') }}</a>
                 </div>
               </div>
            </div>
            <div class="card-body shadow">
                <form action="{{ route('category.update', $category->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="name-en" class="mb-2">{{ __('Name (In English)')}}</label>
                        <input type="text" name="name_en" autocomplete="off" class="form-control" value="{{ $category->name_en }}">
                        </div>
                      </div>
                      <div class="col-md-4">
                       <div class="form-group">
                        <label for="name-en" class="mb-2">{{ __('Name (In Bangla)')}}</label>
                        <input type="text"  name="name_bn" autocomplete="off" class="form-control" value="{{ $category->name_bn }}">
                       </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1" class="mb-2">{{ __('Status') }}</label>
                            <select name="is_active" class="form-control" id="exampleFormControlSelect1">
                              <option value="1" {{ 1 == $category->is_active ? 'selected="selected"' : '' }}>{{ __('Active') }}</option>
                              <option value="0" {{ 0 == $category->is_active ? 'selected="selected"' : '' }}>{{ __('InActive') }}</option>
                            </select>
                          </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 float-right">{{ __('Update') }}</button>
                  </form>
            </div>
        </div>
    </div>
  </main>

@endsection

@section('scripts')

@endsection