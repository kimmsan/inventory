<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;

class ClientInvoicePaymentNotification extends Notification
{
    use Queueable;
    protected $via_data, $invoices;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoices, $via_data)
    {
        $this->invoices = $invoices;
        $this->via_data = $via_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->via_data['isSendEmail'] && $this->via_data['isSendSMS']) {
            return ['mail', TwilioChannel::class];
        } elseif ($this->via_data['isSendEmail'] && ($this->via_data['isSendSMS'] == false)) {
            return ['mail'];
        } elseif ($this->via_data['isSendSMS'] && ($this->via_data['isSendEmail'] == false)) {
            return [TwilioChannel::class];
        } else {
            return [];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->view(
            'emails.client-invoice-payment', ['invoices' => $this->invoices]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toTwilio($notifiable)
    {
        $invoiceNumbers = $invoiceTotals = $paidAmounts = $invoiceTotalPaidAmounts = $invoiceDues ='';
        foreach ($this->invoices as $index => $invoice) {
            $invoiceNumber = config('config.purchasePrefix') . '-' . $invoice['invoice_no'];
            $invoiceTotal = formatCurrency($invoice->invoiceTotal());
            $paidAmount = formatCurrency($invoice['amount_paid']);
            $invoiceTotalPaid = formatCurrency($invoice->invoiceTotalPaid());
            $invoiceDue =  formatCurrency($invoice->totalDue());

            if($index > 0 ){
                $invoiceNumbers .= ', ' . $invoiceNumber;
                $invoiceTotals .= ', ' . $invoiceTotal;
                $paidAmounts .= ', ' . $paidAmount;
                $invoiceTotalPaidAmounts .=  ', ' . $invoiceTotalPaid;
                $invoiceDues .=  ', ' . $invoiceDue;

            }else{
                $invoiceNumbers .= '' . $invoiceNumber;
                $invoiceTotals .= '' . $invoiceTotal;
                $paidAmounts .= ', ' . $paidAmount;
                $invoiceTotalPaidAmounts .=  '' . $invoiceTotalPaid;
                $invoiceDues .=  '' . $invoiceDue;
            }
        }

        // generate message
        $message = 'Hi ' .  $notifiable->name . ', thanks! We have successfully received your payment for the invoices. Below are the payment details for your reference.' . "\n\n";
        $message .=  'Invoice No: ' . $invoiceNumbers . "\n";
        $message .=  'Total: ' . $invoiceTotals . "\n";
        $message .=  'Paid Now: ' . $paidAmounts . "\n";
        $message .=  'Total Paid: ' .  $invoiceTotalPaidAmounts . "\n";
        $message .=  'Due: ' .  $invoiceDues . "\n";

        $message .=  '-------------------------------' . "\n";
        $message .=  'Total Invoice Due: ' .   formatCurrency($notifiable->clientDue()) . "\n";
        $message .=  'Total Non Invoice Due: ' .  formatCurrency($notifiable->nonInvoiceCurrentDue()) . "\n";
        $message .=  'Total Due: ' .  formatCurrency($notifiable->clientDue() +  $notifiable->nonInvoiceCurrentDue()). "\n\n";
        $message .=  'Thanks' . "\n";
        $message .=  config('config.companyName') . "\n";

        return (new TwilioSmsMessage())->content($message);
    }
}
