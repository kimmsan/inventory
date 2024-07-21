<?php

namespace App\Http\Controllers\API;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Resources\CurrencyResource;
use App\Http\Requests\SMSConfigurationRequest;
use App\Http\Requests\MailConfigurationRequest;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class GeneralController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:general-settings', ['only' => ['updateGeneralSettings']]);
        $this->middleware('can:mail-configuration', ['only' => ['updateGeneralSettings', 'updateMailConfiguration']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGeneralSettings()
    {
        $query = GeneralSetting::get();
        $settings = [
            'companyName' => $query->where('key', 'company_name')->first()->value,
            'companyTagline' => $query->where('key', 'company_tagline')->first()->value,
            'email' => $query->where('key', 'email_address')->first()->value,
            'phone' => $query->where('key', 'phone_number')->first()->value,
            'address' => $query->where('key', 'address')->first()->value,
            'clientPrefix' => $query->where('key', 'client_prefix')->first()->value,
            'supplierPrefix' => $query->where('key', 'supplier_prefix')->first()->value,
            'employeePrefix' => $query->where('key', 'employee_prefix')->first()->value,
            'proCatPrefix' => $query->where('key', 'product_cat_prefix')->first()->value,
            'proSubCatPrefix' => $query->where('key', 'product_sub_cat_prefix')->first()->value,
            'productPrefix' => $query->where('key', 'product_prefix')->first()->value,
            'expCatPrefix' => $query->where('key', 'exp_cat_prefix')->first()->value,
            'expSubCatPrefix' => $query->where('key', 'exp_sub_cat_prefix')->first()->value,
            'purchasePrefix' => $query->where('key', 'pur_prefix')->first()->value,
            'purchaseReturnPrefix' => $query->where('key', 'pur_return_prefix')->first()->value,
            'quotationPrefix' => $query->where('key', 'quotation_prefix')->first()->value,
            'invoicePrefix' => $query->where('key', 'invoice_prefix')->first()->value,
            'invoiceReturnPrefix' => $query->where('key', 'invoice_return_prefix')->first()->value,
            'adjustmentPrefix' => $query->where('key', 'adjustment_prefix')->first()->value,
            'currency' => new CurrencyResource(Currency::where(
                'id',
                (int) $query->where('key', 'default_currency')->first()->value
            )->first()),
            'language' => $query->where('key', 'default_language')->first()->value,
            'logo' => asset('images/' . $query->where('key', 'logo')->first()->value),
            'blackLogo' => asset('images/' . $query->where('key', 'logo_black')->first()->value),
            'smallLogo' => asset('images/' . $query->where('key', 'small_logo')->first()->value),
            'favicon' => asset('images/' . $query->where('key', 'favicon')->first()->value),
            'copyright' => $query->where('key', 'copyright')->first()->value,
            'defaultClientSlug' => $query->where('key', 'default_client_slug')->first()->value,
            'defaultAccountSlug' => $query->where('key', 'default_account_slug')->first()->value,
            'defaultVatRateSlug' => $query->where('key', 'default_vat_rate_slug')->first()->value,
        ];

        return $settings;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateGeneralSettings(Request $request)
    {
        $validator = $request->validate([
            'companyName' => 'required|string|max:30',
            'companyTagline' => 'required|string|max:255|min:3',
            'emailAddress' => 'required|string|email|max:80',
            'phoneNumber' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'clientPrefix' => 'required|string|min:2|max:10',
            'supplierPrefix' => 'required|string|min:2|max:10',
            'employeePrefix' => 'required|string|min:2|max:10',
            'proCatPrefix' => 'required|string|min:2|max:10',
            'proSubCatPrefix' => 'required|string|min:2|max:10',
            'productPrefix' => 'required|string|min:2|max:10',
            'expCatPrefix' => 'required|string|min:2|max:10',
            'expSubCatPrefix' => 'required|string|min:2|max:10',
            'purchasePrefix' => 'required|string|min:2|max:10',
            'purchaseReturnPrefix' => 'required|string|min:2|max:10',
            'quotationPrefix' => 'required|string|min:2|max:10',
            'invoicePrefix' => 'required|string|min:2|max:10',
            'invoiceReturnPrefix' => 'required|string|min:2|max:10',
            'adjustmentPrefix' => 'required|string|min:2|max:10',
            'currency' => 'required',
            'defaultClient' => 'required',
            'language' => 'required|string|min:2|max:10',
            'copyrightText' => 'required|string|max:100',
            'defaultAccount' => 'required',
            'defaultAccount' => 'required',
            'defaultVatRate' => 'required',
        ]);


        // get settings data
        $allSettings = GeneralSetting::get();

        // upload logo
        $logoName = $allSettings->where('key', 'logo')->first()->value;
        if ($request->logo) {
            if ($logoName) {
                @unlink(public_path('images/' . $logoName));
            }
            $logoName = 'logo' . '.' . explode(
                '/',
                explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1]
            )[1];
            Image::make($request->logo)->save(public_path('images/') . $logoName);
        }

        // upload black logo
        $blackLogoName = $allSettings->where('key', 'logo_black')->first()->value;
        if ($request->blackLogo) {
            if ($blackLogoName) {
                @unlink(public_path('images/' . $blackLogoName));
            }
            $blackLogoName = 'logo-black' . '.' . explode(
                '/',
                explode(':', substr($request->blackLogo, 0, strpos($request->blackLogo, ';')))[1]
            )[1];
            Image::make($request->blackLogo)->save(public_path('images/') . $blackLogoName);
        }

        // upload small logo
        $smallLogoName = $allSettings->where('key', 'small_logo')->first()->value;
        if ($request->smallLogo) {
            if ($smallLogoName) {
                @unlink(public_path('images/' . $smallLogoName));
            }
            $smallLogoName = 'small-logo' . '.' . explode(
                '/',
                explode(':', substr($request->smallLogo, 0, strpos($request->smallLogo, ';')))[1]
            )[1];
            Image::make($request->smallLogo)->save(public_path('images/') . $smallLogoName);
        }

        // upload favicon
        $favicon = $allSettings->where('key', 'favicon')->first()->value;
        if ($request->favicon) {
            if ($favicon) {
                @unlink(public_path('images/' . $favicon));
            }
            $favicon = 'favicon' . '.' . explode(
                '/',
                explode(':', substr($request->favicon, 0, strpos($request->favicon, ';')))[1]
            )[1];
            Image::make($request->favicon)->save(public_path('images/') . $favicon);
        }

        // update general settings
        $allSettings->where('key', 'company_name')->first()->update(['value' => clean($request->companyName)]);
        $allSettings->where('key', 'company_tagline')->first()->update(['value' => clean($request->companyTagline)]);
        $allSettings->where('key', 'email_address')->first()->update(['value' => $request->emailAddress]);
        $allSettings->where('key', 'phone_number')->first()->update(['value' => $request->phoneNumber]);
        $allSettings->where('key', 'address')->first()->update(['value' => clean($request->address)]);
        $allSettings->where('key', 'client_prefix')->first()->update(['value' => clean($request->clientPrefix)]);
        $allSettings->where('key', 'supplier_prefix')->first()->update(['value' => clean($request->supplierPrefix)]);
        $allSettings->where('key', 'employee_prefix')->first()->update(['value' => clean($request->employeePrefix)]);
        $allSettings->where('key', 'product_cat_prefix')->first()->update(['value' => clean($request->proCatPrefix)]);
        $allSettings->where(
            'key',
            'product_sub_cat_prefix'
        )->first()->update(['value' => clean($request->proSubCatPrefix)]);
        $allSettings->where('key', 'product_prefix')->first()->update(['value' => clean($request->productPrefix)]);
        $allSettings->where('key', 'exp_cat_prefix')->first()->update(['value' => clean($request->expCatPrefix)]);
        $allSettings->where(
            'key',
            'exp_sub_cat_prefix'
        )->first()->update(['value' => clean($request->expSubCatPrefix)]);
        $allSettings->where('key', 'pur_prefix')->first()->update(['value' => clean($request->purchasePrefix)]);
        $allSettings->where(
            'key',
            'pur_return_prefix'
        )->first()->update(['value' => clean($request->purchaseReturnPrefix)]);
        $allSettings->where('key', 'quotation_prefix')->first()->update(['value' => clean($request->quotationPrefix)]);
        $allSettings->where('key', 'invoice_prefix')->first()->update(['value' => clean($request->invoicePrefix)]);
        $allSettings->where(
            'key',
            'invoice_return_prefix'
        )->first()->update(['value' => clean($request->invoiceReturnPrefix)]);
        $allSettings->where(
            'key',
            'adjustment_prefix'
        )->first()->update(['value' => clean($request->adjustmentPrefix)]);
        $allSettings->where('key', 'default_currency')->first()->update(['value' => clean($request->currency['id'])]);
        $allSettings->where('key', 'default_language')->first()->update(['value' => clean($request->language)]);
        $allSettings->where('key', 'logo')->first()->update(['value' => $logoName]);
        $allSettings->where('key', 'logo_black')->first()->update(['value' => $blackLogoName]);
        $allSettings->where('key', 'small_logo')->first()->update(['value' => $smallLogoName]);
        $allSettings->where('key', 'favicon')->first()->update(['value' => $favicon]);
        $allSettings->where('key', 'copyright')->first()->update(['value' => clean($request->copyrightText)]);
        $allSettings->where(
            'key',
            'default_client_slug'
        )->first()->update(['value' => clean($request->defaultClient['slug'])]);
        $allSettings->where(
            'key',
            'default_account_slug'
        )->first()->update(['value' => clean($request->defaultAccount['slug'])]);
        $allSettings->where(
            'key',
            'default_vat_rate_slug'
        )->first()->update(['value' => clean($request->defaultVatRate['slug'])]);


        return redirect()->back()->withSuccess('Settings updated successfully!');
    }

    public function getMailConfiguration()
    {
        $editor = DotenvEditor::load();
        $data = [
            'mail_mailer' => $editor->getKey('MAIL_MAILER'),
            'mail_host' => $editor->getKey('MAIL_HOST'),
            'mail_port' =>  $editor->getKey('MAIL_PORT'),
            'mail_username' => $editor->getKey('MAIL_USERNAME'),
            'mail_password' => $editor->getKey('MAIL_PASSWORD'),
            'mail_encryption' => $editor->getKey('MAIL_ENCRYPTION'),
            'mail_from_address' => $editor->getKey('MAIL_FROM_ADDRESS')
        ];
        return $data;
    }

    public function getSMSConfiguration()
    {
        $editor = DotenvEditor::load();

        $data = [
            'twilio_account_sid' => $editor->getKey('TWILIO_ACCOUNT_SID'),
            'twilio_auth_token' => $editor->getKey('TWILIO_AUTH_TOKEN'),
            'twilio_from' =>  $editor->getKey('TWILIO_FROM'),
            'twilio_sms_service_sid' => $editor->getKey('TWILIO_SMS_SERVICE_SID')
        ];
        return $data;
    }

    public function updateMailConfiguration(MailConfigurationRequest $request)
    {
        $editor = DotenvEditor::load();
        $editor->setKey('MAIL_MAILER', $request->mail_mailer);
        $editor->setKey('MAIL_HOST', $request->mail_host);
        $editor->setKey('MAIL_PORT', $request->mail_port);
        $editor->setKey('MAIL_USERNAME', $request->mail_username);
        $editor->setKey('MAIL_PASSWORD', $request->mail_password);
        $editor->setKey('MAIL_ENCRYPTION', $request->mail_encryption);
        $editor->setKey('MAIL_FROM_ADDRESS', $request->mail_from_address);
        $editor->save();
        return 'Env file updated successfully!';
    }


    public function updateSMSConfiguration(SMSConfigurationRequest $request)
    {
        $editor = DotenvEditor::load();
        $editor->setKey('TWILIO_ACCOUNT_SID', $request->twilio_account_sid);
        $editor->setKey('TWILIO_AUTH_TOKEN', $request->twilio_auth_token);
        $editor->setKey('TWILIO_FROM', $request->twilio_from);
        $editor->setKey('TWILIO_SMS_SERVICE_SID', $request->twilio_sms_service_sid);
        $editor->save();
        return 'Env file updated successfully!';
    }
}