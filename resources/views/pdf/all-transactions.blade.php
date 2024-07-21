@extends('pdf')

@section('content-area')
    <h3>@lang('All transactions: ')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Account')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Status')</th>
                    <th class="text-right">@lang('User')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $key => $transaction)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $transaction['reason'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction['transaction_date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($transaction['type'] == 1)
                                <span class="badge bg-primary">@lang('Credit')</span>
                            @else
                                <span class="badge bg-danger">@lang('Debit')</span>
                            @endif
                        </td>
                        <td>{{ $transaction['cashbook_account']['account_number'] }}</td>
                        <td>
                            @currency($transaction['amount'])
                        </td>
                        <td>
                            @if ($transaction['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                        <td class="text-right">{{ $transaction['user']['name'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
