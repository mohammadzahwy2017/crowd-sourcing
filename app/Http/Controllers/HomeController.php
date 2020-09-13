<?php

namespace App\Http\Controllers;

use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database;
use Illuminate\Support\Facades\DB;
Use App\Notifications\TaskCompleted;
use Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //Verifying Account
    {
        if($new  = Auth::user()->new_account == 1){ //Checking if account is a new account
            $admin = User::where('role','admin')->first()->id;

            $user = User::find($admin);
            Notification::send($user, new TaskCompleted('New User Registered'));

            $auto_approve = Setting::select(array('id'))->first()->id; //Looking for autoapprove settings
            //update new acccount =0
            $id =Auth::user()->id;
            User::where('id', $id)->update(array('new_account' => 0));
            
            if($auto_approve == 1){ //Auto Verify Account since autoapprove is on
                //accepted =1
                User::where('id', $id)->update(array('accepted' => 1));
                return view('home');

            }
            else{   //Redirect user to waiting screen
                return view('wait')->with('data', "Account Awaiting Admin's Verification");
            }
        }

        $role =Auth::user()->role;
        $autoapprove = Setting::get();
        if($role =='admin') {   //Redirect Admin to Admin Screen
            $waiting_accept = User::where('accepted', 0)->get();
            return view('admin')->with(array('waiting_users' => $waiting_accept, 'autoapprove' => $autoapprove[0]->id));
        }
        if($role == 'user') {//Check if OldUserAccount is accepted or not
            $accepted = Auth::user()->accepted;
            if($accepted == 1)
                return view('home');
            else{
                return view('wait')->with('data', "Account Awaiting Admin's Verification");
            }
        }
        return view('home');

    }
}
