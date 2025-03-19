<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaid extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Confirmed for Order #' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment for order #' . $this->order->order_number . ' has been confirmed.')
            ->line('Your order is now being processed by ' . $this->order->canteen->name . '.')
            ->line('You will receive another notification when your order is ready for pickup.')
            ->action('View Order Details', route('buyer.orders.show', $this->order))
            ->line('Thank you for using our campus canteen system!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => 'Your payment for order #' . $this->order->order_number . ' has been confirmed.',
        ];
    }
}

