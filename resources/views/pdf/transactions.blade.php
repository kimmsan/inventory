@extends('pdf')

@section('content-area')
    <h3>@lang('Ledger')</h3>
    <strong>@lang('Bank Name')</strong>: {{ $transactions[0]['cashbook_account']['bank_name'] }}<br />
    <strong>@lang('Branch Name')</strong>: {{ $transactions[0]['cashbook_account']['branch_name'] }}<br />
    <strong>@lang('Account Number')</strong>: {{ $transactions[0]['cashbook_account']['account_number'] }}<br />
    <strong>@lang('Date')</strong>: {{ date('Y-m-d') }}
    <hr />
    <br />
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Particular')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Credit')</th>
                    <th>@lang('Debit')</th>
                    <th>@lang('Balance')</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $balance = 0;
                @endphp
                @foreach ($transactions as $key => $transaction)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td> {{ $transaction['reason'] }}</td>
                        <td>{{ $transaction['transaction_date'] }}</td>
                        <td>
                            @if ($transaction['type'] == 1)
                                @currency($transaction['amount'])
                            @else
                                @currency(0)
                            @endif
                        </td>
                        <td>
                            @if ($transaction['type'] == 0)
                                @currency(0)
                            @else
                                @currency($transaction['amount'])
                            @endif
                        </td>
                        <td>
                            @php
                                $balance = $transaction['type'] == 1 ? $balance + $transaction['amount'] : $balance - $transaction['amount'];
                            @endphp
                            @currency($balance)
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
