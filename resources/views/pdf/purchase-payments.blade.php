@extends('pdf')

@section('content-area')
    <h3>@lang('All purchase payments')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Purchase No')</th>
                    <th>@lang('Supplier')</th>
                    <th>@lang('Total')</th>
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
                        <td>{{ $payment['purchase']['supplier']['name'] }}</td>
                        <td>{{ config('config.purchasePrefix') . '-' . $payment['purchase']['purchase_no'] }}</td>
                        <td>@currency($payment['purchase']['calculated_total'])</td>
                        <td>@currency($payment['amount'])</td>
                        <td>
                            @if (isset($payment['purchase_payment_transaction']))
                                {{ $payment['purchase_payment_transaction']['cashbook_account']['bank_name'] }}
                                [{{ $payment['purchase_payment_transaction']['cashbook_account']['account_number'] }}]
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
