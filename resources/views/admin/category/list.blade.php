@extends('admin-layouts.master')

@section('styles')
@endsection

@section('admin_content')
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">{{ __('Categories') }}</h1>
            
                <div class="row">
                    <div class="col-md-6">
                        <i class="fas fa-plus me-1"></i>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">{{ __('Category') }}</li>
                            <li class="breadcrumb-item active"><a href="{{ route('category.list') }}">{{ __('List') }}</a></li>
                        </ol>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="{{ route('category.create') }}" type="button" class="btn-sm btn-primary"><i
                                class="fas fa-plus me-1"></i>{{ __('Add') }}</a>
                    </div>
                </div>
            
            
                @if( ! $allCategories->isEmpty() || filter_input(INPUT_GET, 'filter') )
                @include('admin.category.search')
                @endif

            <div class="card mb-4">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr class="bg-secondary text-white">
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Name (In English)') }}</th>
                                <th>{{ __('Name (In Bangla)') }}</th>
                                <th>{{ __('Is Active?') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr  class="bg-secondary text-white">
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Name (In English)') }}</th>
                                <th>{{ __('Name (In Bangla)') }}</th>
                                <th>{{ __('Is Active?') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($allCategories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $category->name_en }}</td>
                                    <td>{{ $category->name_bn }}</td>
                                    <td>
                                      @if($category->is_active === 1)
                                      <button class="btn-xs btn-success">{{ __('Active') }}</button>
                                      @elseif($category->is_active === 0)
                                      <button class="btn-xs btn-info">{{ __('Inactive') }}</button>
                                      @endif
                                    </td>
                                    <td>{{ $category->created_at }}</td>
                                    {{-- <td>
                                        <span>
                                            <a href="" type="button" class="btn-sm btn-primary"><i
                                                    class="fas fa-eye me-1"></i></a>
                                        </span>
                                        <span>
                                            <a href="{{ route('category.edit', $category->id) }}" type="button"
                                                class="btn-sm btn-info"><i
                                                    class="fas fa-pen me-1"></i></a>
                                        </span>
                                        <span>
                                            <form action="{{ route('category.delete', $category->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn-sm btn-danger"><i
                                                        class="fas fa-trash me-1"></i></button>
                                            </form>
                                        </span>
                                    </td> --}}
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="row-action-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ __('Actions') }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="row-action-button-1">
                                                <a href="" class="dropdown-item">
                                                    <i class="fa-solid fa-eye"></i> {{ __('View') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('category.edit', $category->id) }}" class="dropdown-item">
                                                    <i class="fa-solid fa-pen-to-square"></i> {{ __('Edit') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                              <form action="{{ route('category.delete', $category->id) }}" method="POST">
                                                <button type="submit" onclick="return confirm('Are you sure?')" class="delete-user btn btn-link text-danger dropdown-item">
                                                    <i class="fa-solid fa-trash"></i> {{ __('Delete') }}
                                                </button>
                                                @csrf
                                                @method('delete')
                                            </form>
                                            </div>
                                          </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
@endsection
