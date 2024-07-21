@extends('pdf')

@section('content-area')
    <h3>@lang('All employees list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Emp ID')</th>
                    <th>@lang('Department')</th>
                    <th>@lang('Designation')</th>
                    <th>@lang('Salary')</th>
                    <th>@lang('Mobile')</th>
                    <th>@lang('Join Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $key => $employee)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $employee['name'] }}</td>
                        <td>{{ config('config.employeePrefix') .'-'. $employee['emp_id'] }}</td>
                        <td>{{ $employee['department']['name'] }}</td>
                        <td>{{ $employee['designation'] }}</td>
                        <td>
                          @currency($employee['salary'])
                        </td>
                        <td>{{ $employee['mobile_number'] }}</td>
                        <td>{{ $employee['joining_date'] }}</td>
                        <td>
                            @if ($employee['status'])
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
