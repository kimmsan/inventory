@extends('pdf')

@section('content-area')
    <h3>@lang('Invoice payments list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Invoice No')</th>
                    <th>@lang('Client')</th>
                    <th>@lang('Total')</th>
                    <th>@lang('Paid Amount')</th>
                    <th>@lang('Account')</th>
                    <th>@lang('Payment Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $key => $invoicePayment)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>
                            {{ config('config.invoicePrefix') . '-' . $invoicePayment['invoice']['invoice_no'] }}
                        </td>
                        <td>{{ $invoicePayment['invoice']['client']['name'] }}</td>
                        <td>
                            @currency($invoicePayment['invoice']['calculated_total'])
                        </td>
                        <td>
                            @currency($invoicePayment['amount'])
                        </td>
                        <td>
                            @if (isset($invoicePayment['invoice_payment_transaction']))
                                {{ $invoicePayment['invoice_payment_transaction']['cashbook_account']['bank_name'] }}
                                [{{ $invoicePayment['invoice_payment_transaction']['cashbook_account']['account_number'] }}]
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($invoicePayment['date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($invoicePayment['status'])
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
