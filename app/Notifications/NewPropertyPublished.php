<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPropertyPublished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('New Property Published!')
                    ->line("Property Name: {$this->property->title}")
                    ->line("Description: {$this->property->description}")
                    ->line("Location: {$this->property->province->name} - {$this->property->city->name} - {$this->property->subDistrict->name}")
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->property->title,
            'description' => $this->property->description,
            'province' => $this->property->province->name,
            'city' => $this->property->city->name,
            'sub_district' => $this->property->subDistrict->name,
            'offer_type' => $this->property->offer_type,
            'price' => $this->property->price,
            'type' => $this->property->category->name,
            'subtype' => $this->property->subCategory->name,
            'image' => $this->property->image,
        ];
    }
}
