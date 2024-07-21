<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;

class SupplierPurchasePaymentNotification extends Notification
{
    use Queueable;
    protected $via_data, $purchases;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($purchases, $via_data)
    {
        $this->purchases = $purchases;
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
            'emails.supplier-purchase-payment', ['purchases' => $this->purchases]
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

        $purchaseNumbers = $purchaseTotals = $paidAmounts = $purchaseTotalPaidAmounts = $purchaseDues ='';
        foreach ($this->purchases as $index => $purchase) {
            $purchaseNumber = config('config.purchasePrefix') . '-' . $purchase['purchase_no'];
            $purchaseTotal = formatCurrency($purchase->purchaseTotal());
            $paidAmount = formatCurrency($purchase['amount_paid']);
            $purchaseTotalPaid = formatCurrency($purchase->purchaseTotalPaid());
            $purchaseDue =  formatCurrency($purchase->totalDue());

            if($index > 0 ){
                $purchaseNumbers .= ', ' . $purchaseNumber;
                $purchaseTotals .= ', ' . $purchaseTotal;
                $paidAmounts .= ', ' . $paidAmount;
                $purchaseTotalPaidAmounts .=  ', ' . $purchaseTotalPaid;
                $purchaseDues .=  ', ' . $purchaseDue;

            }else{
                $purchaseNumbers .= '' . $purchaseNumber;
                $purchaseTotals .= '' . $purchaseTotal;
                $paidAmounts .= ', ' . $paidAmount;
                $purchaseTotalPaidAmounts .=  '' . $purchaseTotalPaid;
                $purchaseDues .=  '' . $purchaseDue;
            }
        }

        // generate message
        $message = 'Hi ' .  $notifiable->name . ', thanks! We have successfully paid your payment for the purchases. Below are the payment details for your reference.' . "\n\n";
        $message .=  'Purchase No: ' . $purchaseNumbers . "\n";
        $message .=  'Total: ' . $purchaseTotals . "\n";
        $message .=  'Paid Now: ' . $paidAmounts . "\n";
        $message .=  'Total Paid: ' .  $purchaseTotalPaidAmounts . "\n";
        $message .=  'Due: ' .  $purchaseDues . "\n";

        $message .=  '-------------------------------' . "\n";
        $message .=  'Total Purchase Due: ' .   formatCurrency($notifiable->purchaseTotalDue()) . "\n";
        $message .=  'Total Non Purchase Due: ' .  formatCurrency($notifiable->nonPurchaseCurrentDue()) . "\n";
        $message .=  'Total Due: ' .  formatCurrency($notifiable->purchaseTotalDue() +  $notifiable->nonPurchaseCurrentDue()). "\n\n";
        $message .=  'Thanks' . "\n";
        $message .=  config('config.companyName') . "\n";

        return (new TwilioSmsMessage())->content($message);
    }
}