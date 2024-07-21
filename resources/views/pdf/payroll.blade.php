@extends('pdf')

@section('content-area')
    <h3>@lang('All payroll list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Emp Name')</th>
                    <th>@lang('Emp ID')</th>
                    <th>@lang('Designation')</th>
                    <th>@lang('Salary Month')</th>
                    <th>@lang('Total Paid')</th>
                    <th>@lang('Account')</th>
                    <th>@lang('Salary Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allPayroll as $key => $payroll)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $payroll['employee']['name'] }}</td>
                        <td>{{ config('config.employeePrefix') . '-' . $payroll['employee']['emp_id'] }}</td>
                        <td>{{ $payroll['employee']['designation'] }}</td>
                        <td>{{ $payroll['salary_month'] }}</td>
                        <td>@currency($payroll['payroll_transaction']['amount'])</td>
                        <td>{{ $payroll['payroll_transaction']['cashbook_account']['account_number'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($payroll['salary_date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($payroll['status'])
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
