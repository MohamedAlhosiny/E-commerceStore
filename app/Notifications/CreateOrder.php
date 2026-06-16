<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateOrder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $orderPrice;
    protected $userName;
    protected $orderId;

    public function __construct($orderPrice , $userName , $orderId)
    {
        $this->orderPrice = $orderPrice;
        $this->userName = $userName;
        $this->orderId = $orderId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail' , 'database'];
    }



    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('your order has been created successfully')
            ->greeting('Hello ' . $this->userName)
            ->line('Your order has been created successfully with ID: ' . $this->orderId)
            // ->action('Notification Action', url('/'))
            ->line('We will review your order soon and notify you of any updates.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

            'order_id' => $this->orderId,
            'order_price' => $this->orderPrice,
            'user_name' => $this->userName,
            'message' => 'Your order has been created successfully'

        ];
    }
}
