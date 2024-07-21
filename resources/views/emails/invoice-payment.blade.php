@extends('email')

@section('email-body')
    <tr>
        <td class="email-body" width="570" cellpadding="0" cellspacing="0">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">
                        <div class="f-fallback">
                            <h1>Hi {{ $invoice['client']['name'] }}, thanks!</h1>
                            <p>
                                @lang('We have successfully received your payment for the invoice. Below are the payment details for your reference. For more information, you can also check your original invoice by clicking')
                                <a href="{{ route('email.invoice.pdf', $invoice->slug) }}">here</a>.
                            </p>

                            <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="attributes_content border_bottom">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Invoice No'): </strong>
                                                        {{ config('config.invoicePrefix') . '-' . $invoice->invoice_no }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total'): </strong> @currency($invoice->invoiceTotal())
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Paid Now'): </strong> @currency($invoice['amount_paid'])
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total Paid'): </strong> @currency($invoice->invoiceTotalPaid())
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Due'): </strong> @currency($invoice->totalDue())
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p>@lang('Here is an updated overview of your outstanding dues, including both invoice and non-invoice amounts:')</p>
                            <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="attributes_content">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total Invoice Due'): </strong> @currency($invoice['client']->clientDue())
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total Non Invoice Due'): </strong> @currency($invoice['client']->nonInvoiceCurrentDue())
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total Due'): </strong> @currency($invoice['client']->clientDue() + $invoice['client']->nonInvoiceCurrentDue())
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- Action -->
                            <br />
                            <p>Thanks,
                                <br>{{ config('config.companyName') }},<br />
                                {{ config('config.companyPhoneNumber') }}
                            </p>
                            <!-- Sub copy -->
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
