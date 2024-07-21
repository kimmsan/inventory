@extends('pdf')

@section('content-area')
    <h3>@lang('All invoices')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Invoice No')</th>
                    <th>@lang('Invoice Date')</th>
                    <th>@lang('Client')</th>
                    <th>@lang('Subtotal')</th>
                    <th>@lang('Transport')</th>
                    <th>@lang('Discount')</th>
                    <th>@lang('Tax')</th>
                    <th>@lang('Net Total')</th>
                    <th>@lang('Due')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $key => $invoice)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ config('config.invoicePrefix') . '-' . $invoice['invoice_no'] }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('d-M-Y') }}
                        </td>
                        <td>{{ $invoice['client']['name'] }}</td>
                        <td>@currency($invoice['sub_total'])</td>
                        <td>@currency($invoice['transport'])</td>
                        <td>@currency($invoice['discount'])</td>
                        <td>@currency($invoice['calculated_tax'])</td>
                        <td>@currency($invoice['calculated_total'])</td>
                        <td>@currency($invoice['calculated_due'])</td>
                        <td>
                            @if ($invoice['status'])
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
