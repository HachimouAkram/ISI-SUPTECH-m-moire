<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = DatabaseNotification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->latest()
            ->paginate(20); // <- paginate et pas get()

        return view('pages.admin.notifications.index', compact('notifications'));
    }


    public function show($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        // Marquer comme lue
        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return view('pages.admin.notifications.show', compact('notification'));
    }
}
