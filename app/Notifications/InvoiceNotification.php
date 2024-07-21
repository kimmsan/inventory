<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;


class InvoiceNotification extends Notification
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
            'emails.invoice',
            ['invoice' => $this->invoice]
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
        return (new TwilioSmsMessage())
            ->content("Hi $notifiable->name \nThank you for doing business with us. We have prepared the invoice for your review. Please find the invoice attached below, where you can check and download it for your convenience.\nInvoice: ". asset('pdfs/Invoice-' . $this->invoice->slug.'.pdf'));
    }
}