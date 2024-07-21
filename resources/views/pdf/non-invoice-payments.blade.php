@extends('pdf')

@section('content-area')
    <h3>@lang('All non invoice payments: ')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Client')</th>
                    <th>@lang('Payment Type')</th>
                    <th>@lang('Paid Amount')</th>
                    <th>@lang('Account')</th>
                    <th>@lang('Payment Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $key => $payment)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $payment['client']['name'] }}</td>
                        <td>
                            @if ($payment['type'] == 1)
                                <span class="badge bg-primary">@lang('Due Paid')</span>
                            @else
                                <span class="badge bg-danger">@lang('Due Added')</span>
                            @endif
                        </td>
                        <td>@currency($payment['amount'])</td>
                        <td>
                            @if (isset($payment['payment_transaction']))
                                {{ $payment['payment_transaction']['cashbook_account']['bank_name'] }}
                                [{{ $payment['payment_transaction']['cashbook_account']['account_number'] }}]
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($payment['date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($payment['status'])
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
