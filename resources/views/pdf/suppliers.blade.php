@extends('pdf')

@section('content-area')
    <h3>@lang('All supplier list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped tables-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Supplier ID')</th>
                    <th>@lang('Phone')</th>
                    <th>@lang('Email')</th>
                    <th>@lang('Company')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $key => $supplier)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $supplier['name'] }}</td>
                        <td>{{ config('config.supplierPrefix') . '-' . $supplier['supplier_id'] }}</td>
                        <td>{{ $supplier['phone'] }}</td>
                        <td>{{ $supplier['email'] }}</td>
                        <td>{{ $supplier['company_name'] }}</td>
                        <td>
                            @if ($supplier['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
