<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification implements ShouldQueue
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
        $items = [];
        foreach ($this->order->orderItems as $item) {
            $items[] = $item->quantity . 'x ' . $item->menu->name;
        }

        return (new MailMessage)
            ->subject('New Order #' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new order #' . $this->order->order_number . '.')
            ->line('Customer: ' . $this->order->user->name)
            ->line('Items:')
            ->line(implode("\n", $items))
            ->line('Total: Rp ' . number_format($this->order->total_price, 0, ',', '.'))
            ->action('View Order Details', route('seller.orders.show', $this->order))
            ->line('Please prepare the order and update the status when ready for pickup.');
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
            'message' => 'New order #' . $this->order->order_number . ' received.',
        ];
    }
}

