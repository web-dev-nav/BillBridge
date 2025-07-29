<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class NotificationRead extends Component
{
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification) {
            $notification->update(['read_at' => now()]);

            FilamentNotification::make()
                ->success()
                ->title(__('messages.flash.notification_read_successfully'))
                ->send();
        }
    }

    public function markAllAsRead()
    {

        $notification = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);


        FilamentNotification::make()
            ->success()
            ->title(__('messages.flash.all_notification_read_successfully'))
            ->send();
    }


    public function render()
    {
        $notifications =  Notification::where('user_id', auth()->user()->id)
            ->where('read_at', null)
            ->orderBy('created_at', 'desc')
            ->toBase()
            ->get() ?? collect();

        return view('livewire.notification-read', ['notifications' => $notifications]);
    }
}
