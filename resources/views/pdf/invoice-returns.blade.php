@extends('pdf')

@section('content-area')
    <h3>@lang('All invoice returns')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Client')</th>
                    <th>@lang('Invoice No')</th>
                    <th>@lang('Return No')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('Cost of Return')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returns as $key => $return)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $return['invoice']['client']['name'] }}</td>
                        <td>{{ config('config.invoicePrefix') . '-' . $return['invoice']['invoice_no'] }}</td>
                        <td>{{ config('config.invoiceReturnPrefix') . '-' . $return['return_no'] }}</td>
                        <td>{{ $return['reason'] }}</td>
                        <td>@currency($return['total_return'])</td>
                        <td>{{ \Carbon\Carbon::parse($return['date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($return['status'])
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
