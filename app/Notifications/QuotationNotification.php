<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;

class QuotationNotification extends Notification
{
    use Queueable;

    protected $via_data, $quotation;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($quotation, $via_data)
    {
        $this->via_data = $via_data;
        $this->quotation = $quotation;
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
            'emails.quotation',
            ['quotation' => $this->quotation]
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
            ->content("Hi $notifiable->name \nThank you for expressing interest in our quotation. We have prepared the quotation for your review. Please find the quotation attached below, where you can check and download it for your convenience.\nQuotation: ". asset('pdfs/Quotation-' . $this->quotation->slug.'.pdf'));
    }
}