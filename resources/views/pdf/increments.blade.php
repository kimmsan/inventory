@extends('pdf')

@section('content-area')
    <h3>@lang('All increments list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Emp ID')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('Basic Salary')</th>
                    <th>@lang('Increment Amount')</th>
                    <th>@lang('Present Salary')</th>
                    <th>@lang('Increment Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salIncrements as $key => $salIncrement)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $salIncrement['employee']['name'] }}</td>
                        <td>
                            {{ config('config.employeePrefix') . '-' . $salIncrement['employee']['emp_id'] }}
                        </td>
                        <td>{{ $salIncrement['reason'] }}</td>
                        <td>
                            @currency($salIncrement['increment_amount'])
                        </td>
                        <td>
                            @currency($salIncrement['employee']['salary'])
                        </td>
                        <td>
                            @currency($salIncrement['employee']['calculated_salary'])
                        </td>
                        <td>{{ $salIncrement['increment_date'] }}</td>
                        <td>
                            @if ($salIncrement['status'])
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
