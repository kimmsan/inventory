@extends('pdf')

@section('content-area')
    <h3>@lang('All accounts')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Bank Name')</th>
                    <th>@lang('Branch Name')</th>
                    <th>@lang('Account Number')</th>
                    <th>@lang('Available Balance')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $key => $account)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $account['bank_name'] }}</td>
                        <td>{{ $account['branch_name'] }}</td>
                        <td>{{ $account['account_number'] }}</td>
                        <td>
                            @currency($account['available_balance'])
                        </td>
                        <td>{{ $account['date'] }}</td>
                        <td>
                            @if ($account['status'])
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
