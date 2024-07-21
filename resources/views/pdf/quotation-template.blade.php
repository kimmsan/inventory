@extends('template')

@section('title')
    <title>@lang('Quotation')</title>
@endsection

@section('receiver-info')
    <h1>@lang('Quotation')</h1>
    <p><strong>@lang('Name'):</strong> {{ $quotation['client']['name'] }}</p>
    @if ($quotation['client']['company_name'])
        <p><strong>@lang('Company'):</strong> {{ $quotation['client']['company_name'] }} </p>
    @endif
    <p><strong>@lang('Phone'):</strong> {{ $quotation['client']['phone'] }}</p>
    <p><strong>@lang('Email'):</strong> {{ $quotation['client']['email'] }}</p>
    @if ($quotation['client']['address'])
        <p><strong>@lang('Address'):</strong> {{ $quotation['client']['address'] }}</p>
    @endif
@endsection

@section('pdf-content')
    <table class="invoice-table">
        <tr>
            <th>@lang('Quotation No')</th>
            @if ($quotation['reference'])
                <th>@lang('Reference')</th>
            @endif
            <th>Date</th>
            @if ($quotation['delivery_place'])
                <th>@lang('Delivery Place')</th>
            @endif
        </tr>
        <tr>
            <td>{{ config('config.quotationPrefix') . '-' . $quotation['quotation_no'] }}</td>
            @if ($quotation['reference'])
                <td>{{ $quotation['reference'] }}</td>
            @endif
            <td>{{ $quotation['quotation_date'] }}</td>
            @if ($quotation['delivery_place'])
                <td>{{ $quotation['delivery_place'] }}</td>
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
        @foreach ($quotation['quotationProducts'] as $key => $product)
            <tr>
                <td>{{ $product['product']['name'] }}</td>
                <td>{{ $product['quantity'] . ' ' . $product['product']['productUnit']['code'] }}</td>
                <td>@currency($product['unit_cost'])</td>
                <td class="text-right">@currency($product['unit_cost'] * $product['quantity'])</td>
            </tr>
        @endforeach
        <tr>
            <td class="total" colspan="3">@lang('Subtotal')</td>
            <td class="total">@currency($quotation['sub_total'])</td>
        </tr>
        @if ($quotation['transport'])
            <tr>
                <td class="total" colspan="3">@lang('Transport')</td>
                <td class="total">@currency($quotation['transport'])</td>
            </tr>
        @endif
        @if ($quotation['discount'])
            <tr>
                <td class="total" colspan="3">@lang('Discount')</td>
                <td class="total">@currency($quotation['discount'])</td>
            </tr>
        @endif
        @if ($quotation['total_tax'])
            <tr>
                <td class="total" colspan="3">@lang('TAX')</td>
                <td class="total">@currency($quotation['total_tax'])</td>
            </tr>
        @endif
        <tr>
            <td colspan="3" class="total">@lang('Total')</td>
            <td class="total">@currency($quotation['total_tax'] + $quotation['transport'] + $quotation['sub_total'] - $quotation['discount'])</td>
        </tr>
    </table>
@endsection
