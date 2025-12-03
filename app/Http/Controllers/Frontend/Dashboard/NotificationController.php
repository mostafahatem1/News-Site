<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return view('frontend.dashboard.notification');
    }
    
    public function deleteNotification($id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->firstOrFail();
        $notification->delete();

        Flasher::addSuccess('Notification deleted successfully!!');

        return redirect()->back();

    }


    public function deleteAllNotifications(){
        auth()->user()->notifications()->delete();
        Flasher::addSuccess('All notifications deleted successfully!');
        return redirect()->back();
    }


}
