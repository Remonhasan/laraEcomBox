@extends('admin-layouts.master')

@section('styles')
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
@endsection

@section('admin_content')

<div class="container mx-auto">
    <a href="{{ route('category.create') }}" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
        Add
    </a>
    <div class="flex flex-col">
        <div class="w-full">
            <div class="p-4 border-b border-gray-200 shadow">
                <!-- <table> -->
                <table id="dataTable" class="p-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-8 text text-gray-500">
                                {{ __('SL') }}
                            </th>
                            <th class="p-8 text text-gray-500">
                                {{ __('Name (In English)') }}
                            </th>
                            <th class="p-8 text text-gray-500">
                                {{__('Name (In Bangla)') }}
                            </th>
                            <th class="p-8 text text-gray-500">
                                {{ __('Created_at') }}
                            </th>
                            <th class="px-6 py-2 text text-gray-500">
                                {{ __('View') }}
                            </th>
                            <th class="px-6 py-2 text text-gray-500">
                                {{ __('Edit') }}
                            </th>
                            <th class="px-6 py-2 text text-gray-500">
                                {{ __('Delete') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="whitespace-nowrap">
                            <td class="px-6 py-4 text text-center text-gray-500">
                                3
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text text-gray-900">
                                    Jon doe 3
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text text-gray-500">jhondoe@example.com</div>
                            </td>
                            <td class="px-6 py-4 text text-center text-gray-500">
                                2021-1-12
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="#" class="px-4 py-1 text text-white bg-green-400 rounded">View</a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="#" class="px-4 py-1 text text-white bg-blue-400 rounded">Edit</a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="#" class="px-4 py-1 text text-white bg-red-400 rounded">Delete</a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();

    });
</script>
@endsection