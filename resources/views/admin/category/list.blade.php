@extends('admin-layouts.master')

@section('styles')
@endsection

@section('admin_content')
<main>
    <div class="container-fluid">
        <h1 class="mt-4">{{ __('Categories') }}</h1>

        <div class="row mb-2">
                <ol class="breadcrumb shadow">
                    <i class="fa-solid fa-border-all mt-1 mr-2"></i>
                    <li class="breadcrumb-item">{{ __('Category') }}</li>
                    <li class="breadcrumb-item active"><a href="{{ route('category.list') }}">{{ __('List') }}</a></li>
                </ol>
                <div class="col-md-12 d-flex justify-content-end">
                    <a href="{{ route('category.create') }}" type="button" class="btn-sm btn-primary"><i class="fas fa-plus me-1"></i>{{ __('Add') }}</a>
                </div>
            </div>


            @if (!$categories->isEmpty() || filter_input(INPUT_GET, 'filter'))
                @include('admin.category.search')
            @endif

            @if (!$categories->isEmpty())
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
                            <tr class="bg-secondary text-white">
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Name (In English)') }}</th>
                                <th>{{ __('Name (In Bangla)') }}</th>
                                <th>{{ __('Is Active?') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($categories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $category->name_en }}</td>
                                    <td>{{ $category->name_bn }}</td>
                                    <td>
                                        @if ($category->is_active === 1)
                                            <button class="btn-xs btn-success">{{ __('Active') }}</button>
                                        @elseif($category->is_active === 0)
                                            <button class="btn-xs btn-info">{{ __('Inactive') }}</button>
                                        @endif
                                    </td>
                                    <td>{{ $category->created_at }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                id="row-action-button-1" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                {{ __('Actions') }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="row-action-button-1">
                                                <a href="" class="dropdown-item">
                                                    <i class="fa-solid fa-eye"></i> {{ __('View') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('category.edit', $category->id) }}"
                                                    class="dropdown-item">
                                                    <i class="fa-solid fa-pen-to-square"></i> {{ __('Edit') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('category.delete', $category->id) }}"
                                                    method="POST">
                                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                                        class="delete-user btn btn-link text-danger dropdown-item">
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

                {!! gridFooter($categories, $itemsPerPage) !!}
            @else
                <div class="alert alert-info alert-styled-left" role="alert">
                    {{ __('Sorry! No data found to display') }}
                </div>
            @endif
        </div>
</main>
@endsection

@section('scripts')
@endsection