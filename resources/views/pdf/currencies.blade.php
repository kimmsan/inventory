@extends('pdf')

@section('content-area')
    <h3>@lang('All currencies list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Code')</th>
                    <th>@lang('Symbol')</th>
                    <th>@lang('Position')</th>
                    <th>@lang('Preview')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Created At')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currencies as $key => $currency)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $currency['name'] }}</td>
                        <td>{{ $currency['code'] }}</td>
                        <td>{{ $currency['symbol'] }}</td>
                        <td>{{ $currency['position'] }}</td>
                        <td>@currency(0)</td>
                        <td>
                            @if ($currency['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($currency['created_at'])->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
