<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;

class PurchasePaymentNotification extends Notification
{
    use Queueable;
    protected $via_data, $purchase;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($purchase, $via_data)
    {
        $this->purchase = $purchase;
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
            'emails.purchase-payment', ['purchase' => $this->purchase]
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
        $message = 'Hi ' .  $this->purchase->supplier->name . ', thanks! We have successfully paid your payment for the purchase. Below are the payment details for your reference.' . "\n\n";
        $message .=  'Purchase No: ' . config('config.purchasePrefix') . '-' . $this->purchase->purchase_no . "\n";
        $message .=  'Total: ' . formatCurrency($this->purchase->purchaseTotal()) . "\n";
        $message .=  'Paid Now: ' . formatCurrency($this->purchase['amount_paid']) . "\n";
        $message .=  'Total Paid: ' . formatCurrency($this->purchase->purchaseTotalPaid()) . "\n";
        $message .=  'Due: ' .  formatCurrency($this->purchase->totalDue()) . "\n";

        $message .=  '-------------------------------' . "\n";
        $message .=  'Total Purchase Due: ' .   formatCurrency($this->purchase->supplier->purchaseTotalDue()) . "\n";
        $message .=  'Total Non Purchase Due: ' .  formatCurrency($this->purchase->supplier->nonPurchaseCurrentDue()) . "\n";
        $message .=  'Total Due: ' .  formatCurrency($this->purchase->supplier->purchaseTotalDue() +  $this->purchase->supplier->nonPurchaseCurrentDue()). "\n\n";
        $message .=  'Thanks' . "\n";
        $message .=  config('config.companyName') . "\n";

        return (new TwilioSmsMessage())->content($message);


    }
}
