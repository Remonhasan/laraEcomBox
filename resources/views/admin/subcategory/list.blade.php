@extends('admin-layouts.master')

@section('styles')
@endsection

@section('admin_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">SubCategories</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">{{ __('SubCategory') }}</li>
                <li class="breadcrumb-item active"><a href="{{ route('subcategory.list') }}">{{ __('List') }}</a></li>
            </ol>
            <div class="card mb-4">
                <div class="card-body">
                    DataTables is a third party plugin that is used to generate the demo table below. For more information
                    about DataTables, please visit the
                    <a target="_blank" href="https://datatables.net/">official DataTables documentation</a>
                    .
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <i class="fas fa-table mt-2"></i>
                            DataTable Example
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a href="{{ route('subcategory.create') }}" type="button" class="btn btn-primary"><i
                                    class="fas fa-plus me-1"></i>{{ __('Add') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Name (In English)') }}</th>
                                <th>{{ __('Name (In Bangla)') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Is Active?') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Name (In English)') }}</th>
                                <th>{{ __('Name (In Bangla)') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Is Active?') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($allSubcategories as $key => $subcategory)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $subcategory->name_en }}</td>
                                    <td>{{ $subcategory->name_bn }}</td>
                                    <td>{{ $subcategory->is_active }}</td>
                                    <td>{{ $subcategory->created_at }}</td>
                                    <td>
                                        <span>
                                            <a href="" type="button" class="btn-sm btn-primary"><i
                                                    class="fas fa-eye me-1"></i>{{ __('View') }}</a>
                                        </span>
                                        <span>
                                            <a href="{{ route('subcategory.edit', $subcategory->id) }}" type="button"
                                                class="btn-sm btn-info"><i
                                                    class="fas fa-pen me-1"></i>{{ __('Edit') }}</a>
                                        </span>
                                        <span>
                                            <form action="{{ route('subcategory.delete', $subcategory->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn-sm btn-danger"><i
                                                        class="fas fa-trash me-1"></i>{{ __('Delete') }}</button>
                                            </form>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
@endsection
