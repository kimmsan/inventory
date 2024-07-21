@extends('pdf')

@section('content-area')
    <h3>@lang('All payment methods list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Short Code')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Created At')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentMethods as $key => $paymentMethod)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $paymentMethod['name'] }}</td>
                        <td>{{ $paymentMethod['code'] }}</td>
                        <td>
                            @if ($paymentMethod['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($paymentMethod['created_at'])->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
