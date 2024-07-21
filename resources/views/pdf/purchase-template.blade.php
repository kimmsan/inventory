@extends('template')

@section('title')
    <title>@lang('Purchase Invoice')</title>
@endsection


@section('receiver-info')
    <h1>@lang('Purchase Invoice')</h1>
    <p><strong>@lang('Name'):</strong> {{ $purchase['supplier']['name'] }}</p>
    @if ($purchase['supplier']['company_name'])
        <p><strong>@lang('Company'):</strong> {{ $purchase['supplier']['company_name'] }} </p>
    @endif
    <p><strong>@lang('Phone'):</strong> {{ $purchase['supplier']['phone'] }}</p>
    <p><strong>@lang('Email'):</strong> {{ $purchase['supplier']['email'] }}</p>
    @if ($purchase['supplier']['address'])
        <p><strong>@lang('Address'):</strong> {{ $purchase['supplier']['address'] }}</p>
    @endif
@endsection


@section('pdf-content')
    <table class="invoice-table">s
        <tr>
            <th>@lang('Purchase No')</th>
            @if ($purchase['po_reference'])
                <th>@lang('Reference')</th>
            @endif
            <th>@lang('Date')</th>
            <th>@lang('PO Date')</th>
            @if ($purchase['payment_terms'])
                <th>@lang('Payment Terms')</th>
            @endif
        </tr>
        <tr>
            <td>{{ config('config.purchasePrefix') . '-' . $purchase['purchase_no'] }}</td>
            @if ($purchase['po_reference'])
                <td>{{ $purchase['po_reference'] }}</td>
            @endif
            <td>{{ $purchase['purchase_date'] }}</td>
            <td>{{ $purchase['purchase_date'] }}</td>
            @if ($purchase['payment_terms'])
                <td>{{ $purchase['po_reference'] }}</td>
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
        @foreach ($purchase['purchaseProducts'] as $key => $product)
            <tr>
                <td>{{ $product['product']['name'] }}</td>
                <td>{{ $product['quantity'] . ' ' . $product['product']['productUnit']['code'] }}</td>
                <td>@currency($product['unit_cost'])</td>
                <td class="text-right">@currency($product['unit_cost'] * $product['quantity'])</td>
            </tr>
        @endforeach
        <tr>
            <td class="total" colspan="3">@lang('Subtotal')</td>
            <td class="total">@currency($purchase['sub_total'])</td>
        </tr>
        @if ($purchase['purchaseReturn'])
            <tr>
                <td class="total" colspan="3">@lang('Cost of Return Products')</td>
                <td class="total">@currency($purchase['purchaseReturn']['total_return'])</td>
            </tr>
        @endif
        @if ($purchase['discount'])
            <tr>
                <td class="total" colspan="3">@lang('Discount')</td>
                <td class="total">@currency($purchase['discount'])</td>
            </tr>
        @endif
        @if ($purchase['transport'])
            <tr>
                <td class="total" colspan="3">@lang('Transport')</td>
                <td class="total">@currency($purchase['transport'])</td>
            </tr>
        @endif
        @if ($purchase->taxAmount())
            <tr>
                <td class="total" colspan="3">@lang('Tax')</td>
                <td class="total">@currency($purchase->taxAmount())</td>
            </tr>
        @endif
        <tr>
            <td colspan="3" class="total">@lang('Total')</td>
            <td class="total">@currency($purchase->purchaseTotal())</td>
        </tr>
        <tr>
            <td colspan="3" class="total">@lang('Total Paid')</td>
            <td class="total">@currency($purchase->purchaseTotalPaid())</td>
        </tr>
        <tr>
            <td colspan="3" class="total">@lang('Total Due')</td>
            <td class="total">@currency($purchase->totalDue())</td>
        </tr>
        @php
            $accountReceivable = isset($purchase['purchaseReturn']['returnTransaction']) ? $purchase['purchaseReturn']['returnTransaction']['amount'] : null;
        @endphp
        @if ($accountReceivable)
            <tr>
                <td colspan="3" class="total">@lang('Account Receivable')</td>
                <td class="total">@currency($accountReceivable)</td>
            </tr>
        @endif
    </table>
@endsection
