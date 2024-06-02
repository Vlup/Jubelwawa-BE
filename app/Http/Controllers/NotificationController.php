<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class NotificationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $notifications = Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', auth()->user()->id)
            ->latest()
            ->get()
            ->filter(function (Notification $notif) {
                return $notif->data['title'] === 'Modern House';
            });

        return NotificationResource::collection($notifications);
    }
}
