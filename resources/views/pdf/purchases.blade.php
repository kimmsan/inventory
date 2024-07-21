@extends('pdf')

@section('content-area')
    <h3>@lang('All purchases')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Purchase No')</th>
                    <th>@lang('Purchase Date')</th>
                    <th>@lang('Supplier')</th>
                    <th>@lang('Sub Total')</th>
                    <th>@lang('Transport')</th>
                    <th>@lang('Discount')</th>
                    <th>@lang('Net Total')</th>
                    <th>@lang('Total Due')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $key => $purchase)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td> {{ config('config.purchasePrefix') . '-' . $purchase['purchase_no'] }} </td>
                        <td> {{ \Carbon\Carbon::parse($purchase['purchase_date'])->format('d-M-Y') }} </td>
                        <td> {{ $purchase['supplier']['name'] }} </td>
                        <td> @currency($purchase['sub_total']) </td>
                        <td> @currency($purchase['transport']) </td>
                        <td> @currency($purchase['discount']) </td>
                        <td> @currency($purchase['calculated_total']) </td>
                        <td> @currency($purchase['calculated_due']) </td>
                        <td>
                            @if ($purchase['status'])
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
