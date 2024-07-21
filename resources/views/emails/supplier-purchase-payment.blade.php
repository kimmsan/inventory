@extends('email')

@section('email-body')
    <tr>
        <td class="email-body" width="570" cellpadding="0" cellspacing="0">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <!-- Body content -->
                @php
                    if (isset($purchases) && count($purchases) > 0) {
                        $supplier = $purchases[0]['supplier'];
                    }
                @endphp
                <tr>
                    <td class="content-cell">
                        <div class="f-fallback">
                            <h1>Hi {{ $supplier['name'] }}, thanks!</h1>
                            <p>
                                @lang('We have successfully paid your payment for the purchases. Below are the payment details for your reference. For more information, you can also check your original purchase invoice by clicking the purchase number.')
                            </p>
                            <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                @foreach ($purchases as $key => $purchase)
                                    <tr>
                                        <td class="attributes_content border_bottom">
                                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td class="attributes_item">
                                                        <span class="f-fallback">
                                                            <strong>@lang('Purchase No'): </strong>
                                                            <a
                                                                href="{{ route('email.purchase.pdf', $purchase->slug) }}">{{ config('config.purchasePrefix') . '-' . $purchase->purchase_no }}</a>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="attributes_item">
                                                        <span class="f-fallback">
                                                            <strong>@lang('Total'): </strong> @currency($purchase->purchaseTotal())
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="attributes_item">
                                                        <span class="f-fallback">
                                                            <strong>@lang('Paid Now'): </strong> @currency($purchase['amount_paid'])
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="attributes_item">
                                                        <span class="f-fallback">
                                                            <strong>@lang('Total Paid'): </strong> @currency($purchase->purchaseTotalPaid())
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="attributes_item">
                                                        <span class="f-fallback">
                                                            <strong>@lang('Due'): </strong> @currency($purchase->totalDue())
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <p>@lang('Here is an updated overview of our outstanding dues, including both purchase and non-purchase amounts:')</p>
                            <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="attributes_content">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total Purchase Due'): </strong> @currency($supplier->purchaseTotalDue())
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total Non Purchase Due'): </strong> @currency($supplier->nonPurchaseCurrentDue())
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="attributes_item">
                                                    <span class="f-fallback">
                                                        <strong>@lang('Total Due'): </strong> @currency($supplier->purchaseTotalDue() + $supplier->nonPurchaseCurrentDue())
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
