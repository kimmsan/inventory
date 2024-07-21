<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Helpers\CurrencyHelper;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;

class InvoicePaymentNotification extends Notification
{
    use Queueable;
    protected $via_data, $invoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice, $via_data)
    {
        $this->invoice = $invoice;
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
            'emails.invoice-payment', ['invoice' => $this->invoice]
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
        // generate message
        $message = 'Hi ' .  $this->invoice->client->name . ', thanks! We have successfully received your payment for the invoice. Below are the payment details for your reference.' . "\n\n";
        $message .=  'Invoice No: ' . config('config.invoicePrefix') . '-' . $this->invoice->invoice_no . "\n";
        $message .=  'Total: ' . formatCurrency($this->invoice->invoiceTotal()) . "\n";
        $message .=  'Paid Now: ' . formatCurrency($this->invoice['amount_paid']) . "\n";
        $message .=  'Total Paid: ' . formatCurrency($this->invoice->invoiceTotalPaid()) . "\n";
        $message .=  'Due: ' .  formatCurrency($this->invoice->totalDue()) . "\n";

        $message .=  '-------------------------------' . "\n";
        $message .=  'Total Invoice Due: ' .   formatCurrency($this->invoice->client->clientDue()) . "\n";
        $message .=  'Total Non Invoice Due: ' .  formatCurrency($this->invoice->client->nonInvoiceCurrentDue()) . "\n";
        $message .=  'Total Due: ' .  formatCurrency($this->invoice->client->clientDue() +  $this->invoice->client->nonInvoiceCurrentDue()). "\n\n";
        $message .=  'Thanks' . "\n";
        $message .=  config('config.companyName') . "\n";

        return (new TwilioSmsMessage())->content($message);
    }
}