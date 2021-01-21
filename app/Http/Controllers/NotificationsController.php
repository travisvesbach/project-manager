<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class NotificationsController extends Controller
{

    public function update(Request $request, $notificationId) {
        $notification = auth()->user()->unreadNotifications->where('id', $notificationId)->first();

        if($request->filled('read') && $request->input('read') == true) {
            $notification->markAsRead();
        }
    }

}
