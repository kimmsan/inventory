@extends('template')

@section('title')
    <title>@lang('Sales Invoice')</title>
@endsection


@section('receiver-info')
    <h1>@lang('Invoice')</h1>
    <p><strong>@lang('Name'):</strong> {{ $invoice['client']['name'] }}</p>
    @if ($invoice['client']['company_name'])
        <p><strong>@lang('Company'):</strong> {{ $invoice['client']['company_name'] }} </p>
    @endif
    <p><strong>@lang('Phone'):</strong> {{ $invoice['client']['phone'] }}</p>
    <p><strong>@lang('Email'):</strong> {{ $invoice['client']['email'] }}</p>
    @if ($invoice['client']['address'])
        <p><strong>@lang('Address'):</strong> {{ $invoice['client']['address'] }}</p>
    @endif
@endsection


@section('pdf-content')
    <table class="invoice-table">
        <tr>
            <th>@lang('Invoice No')</th>
            @if ($invoice['reference'])
                <th>@lang('Reference')</th>
            @endif
            <th>Date</th>
            @if ($invoice['delivery_place'])
                <th>@lang('Delivery Place')</th>
            @endif
            @if ($invoice['po_reference'])
                <th>@lang('PO Reference')</th>
            @endif
            @if ($invoice['payment_terms'])
                <th>@lang('Payment Terms')</th>
            @endif
        </tr>
        <tr>
            <td>{{ config('config.invoicePrefix') . '-' . $invoice['invoice_no'] }}</td>
            @if ($invoice['reference'])
                <td>{{ $invoice['reference'] }}</td>
            @endif
            <td>{{ $invoice['invoice_date'] }}</td>
            @if ($invoice['delivery_place'])
                <td>{{ $invoice['delivery_place'] }}</td>
            @endif
            @if ($invoice['po_reference'])
                <td>{{ $invoice['po_reference'] }}</td>
            @endif
            @if ($invoice['payment_terms'])
                <td>{{ $invoice['po_reference'] }}</td>
            @endif
        </tr>
    </table>
    <table class="invoice-table">
        <tr>
            <th>@lang('Item')</th>
            <th>@lang('Quantity')</th>
            <th>@lang('Price')</th>
            <th>@lang('Subtotal')</th>
        </tr>
        @foreach ($invoice['invoiceProducts'] as $key => $product)
            <tr>
                <td>{{ $product['product']['name'] }}</td>
                <td>{{ $product['quantity'] . ' ' . $product['product']['productUnit']['code'] }}</td>
                <td> @currency($product['unit_cost'])</td>
                <td class="text-right"> @currency($product['unit_cost'] * $product['quantity'])</td>
            </tr>
        @endforeach
        <tr>
            <td class="total" colspan="3">@lang('Subtotal')</td>
            <td class="total">@currency($invoice['sub_total'])</td>
        </tr>
        @if ($invoice['invoiceReturn'])
            <tr>
                <td class="total" colspan="3">@lang('Cost of Return Products')</td>
                <td class="total">@currency($invoice['invoiceReturn']['total_return'])</td>
            </tr>
        @endif
        @if ($invoice['discount'])
            <tr>
                <td class="total" colspan="3">@lang('Discount')</td>
                <td class="total">@currency($invoice['discount'])</td>
            </tr>
        @endif
        @if ($invoice['transport'])
            <tr>
                <td class="total" colspan="3">@lang('Transport')</td>
                <td class="total">@currency($invoice['transport'])</td>
            </tr>
        @endif
        @if ($invoice->taxAmount())
            <tr>
                <td class="total" colspan="3">@lang('Tax')</td>
                <td class="total">@currency($invoice->taxAmount())</td>
            </tr>
        @endif
        <tr>
            <td colspan="3" class="total">@lang('Total')</td>
            <td class="total">
                @php
                    $totalInvoiceReturn = isset($invoice['invoiceReturn']) ? $invoice['invoiceReturn']['total_return'] : 0;
                    $total = $invoice['sub_total'] - $totalInvoiceReturn - $invoice['discount'] + $invoice['total_tax'] + $invoice['transport'];
                    $accountPayable = isset($invoice['invoiceReturn']['returnTransaction']) ? $invoice['invoiceReturn']['returnTransaction']['amount'] : null;
                @endphp
                @currency($total)
            </td>
        </tr>
        <tr>
            <td colspan="3" class="total">@lang('Total Paid')</td>
            <td class="total">@currency($invoice->invoiceTotalPaid())</td>
        </tr>
        <tr>
            <td colspan="3" class="total">@lang('Total Due')</td>
            <td class="total">@currency($invoice->totalDue())</td>
        </tr>
        @if ($accountPayable)
            <tr>
                <td colspan="3" class="total">@lang('Account Payable')</td>
                <td class="total">@currency($accountPayable)</td>
            </tr>
        @endif
    </table>
@endsection
