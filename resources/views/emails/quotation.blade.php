@extends('email')

@section('email-body')
    <tr>
        <td class="email-body" width="570" cellpadding="0" cellspacing="0">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">
                        <div class="f-fallback">
                            <h1>Hi {{ $quotation->client->name }}, good day.</h1>
                            <p>Thank you for requesting the quotation. We have prepared the quotation for you, which you can
                                find and download by clicking the link provided below. Feel free to review it at your
                                convenience. If you have any questions or require any further information, please don't
                                hesitate to let us know. We're here to assist you.</p>
                            <p><a href="{{ route('email.quotation.pdf', $quotation->slug) }}">Quotation</a></p>
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
