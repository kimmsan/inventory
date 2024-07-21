@extends('pdf')

@section('content-area')
    <h3>@lang('All loans')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('Ref. No')</th>
                    <th>@lang('Authority')</th>
                    <th>@lang('Account')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Interest')</th>
                    <th>@lang('Payable')</th>
                    <th>@lang('Due')</th>
                    <th>@lang('Installment')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $key => $loan)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $loan->reason }}</td>
                        <td>{{ $loan->reference_no }}</td>
                        <td>{{ $loan->loanAuthority->name }}</td>
                        <td>{{ $loan->loanTransaction->cashbookAccount->account_number }}</td>
                        <td>@currency($loan->loanTransaction->amount)</td>
                        <td>@currency($loan->totalInterest())</td>
                        <td>@currency($loan->payable)</td>
                        <td>@currency($loan->totalDue())</td>
                        <td>{{ $loan->installmentStr() }}</td>
                        <td>
                            @if ($loan->status)
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
