@extends('pdf')

@section('content-area')
    <h3>@lang('All balance transfers')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('From Account')</th>
                    <th>@lang('To Account')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transfers as $key => $transfer)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $transfer['reason'] }}</td>
                        <td>
                            {{ $transfer['debit_transaction']['cashbook_account']['bank_name'] }}
                            [{{ $transfer['debit_transaction']['cashbook_account']['account_number'] }}]
                        </td>
                        <td>
                            {{ $transfer['credit_transaction']['cashbook_account']['bank_name'] }}
                            [{{ $transfer['credit_transaction']['cashbook_account']['account_number'] }}]
                        </td>
                        <td>
                            @currency($transfer['amount'])
                        </td>
                        <td>{{ \Carbon\Carbon::parse($transfer['date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($transfer['status'])
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
