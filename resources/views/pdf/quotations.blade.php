@extends('pdf')

@section('content-area')
    <h3>@lang('Quotations list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Quotation No')</th>
                    <th>@lang('Quotation Date')</th>
                    <th>@lang('Client')</th>
                    <th>@lang('Subtotal')</th>
                    <th>@lang('Transport')</th>
                    <th>@lang('Discount')</th>
                    <th>@lang('Tax')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotations as $key => $quotation)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td> {{ config('config.quotationPrefix') . '-' . $quotation['quotation_no'] }} </td>
                        <td> {{ \Carbon\Carbon::parse($quotation['quotation_date'])->format('d-M-Y') }} </td>
                        <td> {{ $quotation['client']['name'] }} </td>
                        <td> @currency($quotation['sub_total']) </td>
                        <td> @currency($quotation['transport']) </td>
                        <td> @currency($quotation['discount']) </td>
                        <td> @currency($quotation['total_tax']) </td>
                        <td>
                            @if ($quotation['status'])
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
