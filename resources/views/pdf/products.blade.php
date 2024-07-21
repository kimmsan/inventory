@extends('pdf')

@section('content-area')
    <h3>@lang('All product list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Category')</th>
                    <th>@lang('Code')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Item Model')</th>
                    <th>@lang('Stock Qty')</th>
                    <th>@lang('Avg. Puchase Price')</th>
                    <th>@lang('Regular Price')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>
                            {{ $product['proSubCategory']['name'] }}<br />
                            [{{ config('config.proSubCatPrefix') . '-' . $product['proSubCategory']['code'] }}]
                        </td>
                        <td>{{ config('config.productPrefix') . '-' . $product['code'] }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['model'] }}</td>
                        <td>
                            {{ $product['inventory_count'] > 0 ? $product['inventory_count'] : 0 }}
                            {{ $product['productUnit']['code'] }}
                        </td>
                        <td>@currency($product['purchase_price'])</td>
                        <td>@currency($product['regular_price'])</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
