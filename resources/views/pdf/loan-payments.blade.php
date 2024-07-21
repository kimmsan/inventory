@extends('pdf')

@section('content-area')
    <h3>@lang('All loan payment list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Authority')</th>
                    <th>@lang('Loan Ref')</th>
                    <th>@lang('Payment Ref')</th>
                    <th>@lang('Payable')</th>
                    <th>@lang('Paid')</th>
                    <th>@lang('Interest')</th>
                    <th>@lang('Account')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loanPayments as $key => $payment)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $payment->loan->loanAuthority->name }}</td>
                        <td>{{ $payment->loan->reference_no }}</td>
                        <td>{{ $payment->reference_no }}</td>
                        <td>@currency($payment->loan->payable)</td>
                        <td>@currency($payment->amount)</td>
                        <td>@currency($payment->interest)</td>
                        <td>{{ $payment->loanPaymentTransaction->cashbookAccount->account_number }}</td>
                        <td>{{ $payment->date }}</td>
                        <td>
                            @if ($payment->status)
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
