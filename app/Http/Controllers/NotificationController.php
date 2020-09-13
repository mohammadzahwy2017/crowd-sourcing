<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $req){ //Set Clicked Notification as Read
        auth()->user()->notifications->where('id',$req->id)->markAsRead();
    }
}
