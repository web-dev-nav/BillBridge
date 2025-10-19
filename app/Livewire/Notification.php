<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification as NotificationModel;
use Filament\Notifications\Notification as FilamentNotification;


class Notification extends Component
{
    public function markAsRead($notificationId)
    {
        $notification = NotificationModel::find($notificationId);

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
        NotificationModel::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        FilamentNotification::make()
            ->success()
            ->title(__('messages.flash.all_notification_read_successfully'))
            ->send();
    }

    public function render()
    {
        $notifications = NotificationModel::where('user_id', auth()->user()->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get() ?? collect();

        return view('livewire.notification', ['notifications' => $notifications]);
    }
}
