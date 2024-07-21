@extends('pdf')

@section('content-area')
    <h3>@lang('All vat rates list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Short Code')</th>
                    <th>@lang('Rate')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Created At')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vatRates as $key => $vatRate)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $vatRate['name'] }}</td>
                        <td>{{ $vatRate['code'] }}</td>
                        <td>{{ $vatRate['rate'] }}%</td>
                        <td>
                            @if ($vatRate['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($vatRate['created_at'])->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
