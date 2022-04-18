@extends('admin-layouts.master')

@section('styles')
@endsection

@section('admin_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Products</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">{{ __('Product') }}</li>
                <li class="breadcrumb-item active"><a href="{{ route('product.list') }}">{{ __('List') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Add') }}</li>
            </ol>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <i class="fas fa-table mt-2"></i>
                            {{ __('Create New Product') }}
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a href="{{ route('product.list') }}" type="button" class="btn btn-primary"><i
                                    class="fas fa-plus me-1"></i>{{ __('List') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body shadow">
                    <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="category-id" class="mb-2">{{ __('Category') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <select name="category_id" class="form-control" id="category-id">
                                            <option value="">{{ __('Select a Category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $category->id ? 'selected="selected"' : '' }}>
                                                    {{ $category->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="subcategory-id" class="mb-2">{{ __('Subcategory') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <select name="subcategory_id" class="form-control" id="category-id">
                                            <option value="">{{ __('Select a Category') }}</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}"
                                                    {{ $subcategory->id == $subcategory->id ? 'selected="selected"' : '' }}>
                                                    {{ $subcategory->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="name-en" class="mb-2 mt-2">{{ __('Name (In English)') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="text" name="name_en" autocomplete="off" class="form-control"
                                            placeholder="Name (In English)">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="name-bn" class="mb-2 mt-2">{{ __('Name (In Bangla)') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="text" name="name_bn" autocomplete="off" class="form-control"
                                            placeholder="Name (In Bangla)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-label" class="mb-2 mt-2" for="image">{{ __('Image') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="file" name="image" class="form-control" id="productImage" />
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group green-border-focus">
                                            <label for="description" class="mb-2 mt-2">{{ __('Description') }}
                                                <sup class="text-danger">*</sup>
                                            </label>
                                            <textarea class="form-control"  name="description" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="price" class="mb-2 mt-2">{{ __('Price') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="text" name="price" autocomplete="off" class="form-control"
                                            placeholder="Enter price">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="discount" class="mb-2 mt-2">{{ __('Discount') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="text" name="discount" autocomplete="off" class="form-control"
                                            placeholder="Enter discount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="size" class="mb-2 mt-2">{{ __('Size') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="text" name="size" autocomplete="off" class="form-control"
                                            placeholder="Enter price">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="price-with-discount"
                                            class="mb-2 mt-2">{{ __('Price Including Discount') }}
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="text" name="price_with_discount" autocomplete="off"
                                            class="form-control" placeholder="Enter price including discount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="is-active" class="mb-2 mt-2">{{ __('Status') }}</label>
                                        <select name="is_active" class="form-control" id="isActive">
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="is-stock" class="mb-2 mt-2">{{ __('Status') }}</label>
                                        <select name="is_stock" class="form-control" id="isStock">
                                            <option value="1">{{ __('Stock') }}</option>
                                            <option value="0">{{ __('Out of Stock') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 float-right">{{ __('Save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
@endsection
