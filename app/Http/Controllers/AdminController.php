<?php

namespace App\Http\Controllers;

use App\Setting;
use App\User;
use App\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Notification;
Use App\Notifications\TaskCompleted;
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function begins()    //Return to admin view with UnVerified users, and autoapprove settings
    {
        if (auth()->User()->role != 'admin' && auth()->user()->accepted ==1) {
            return view('home');
        }
        if(auth()->user()->accepted ==0){
            return view('wait');
        }
        $r  =0;
        $autoapprove = Setting::get();
        
        $waiting_accept = User::where('accepted', $r)->get();
        return view('admin')->with(array('waiting_users' => $waiting_accept, 'autoapprove' => $autoapprove[0]->id));
    }

    public function editUser(Request $req) //Function used to verify Users
    {
        $autoapprove = Setting::get();

        $id = $req->input('user_id');
        $status = $req->input('press');
        
        if ($status == 1) { //User Accepted
            User::where('id', $id)->update(array('accepted' => 1));
            $users= User::find($id);
            Notification::send($users, new TaskCompleted("You Have Been Accepted, Please Refresh to proceed"));
            $r=0;
            $waiting_accept = User::where('accepted', $r)->get();

            return view('admin')->with(array('waiting_users' => $waiting_accept, 'autoapprove' => $autoapprove[0]->id));
        }
        if ($status == 0) { //User Rejected
            User::where('id', $id)->delete();
            $r=0;
            $waiting_accept = User::where('accepted', $r)->get();

            return view('admin')->with(array('waiting_users' => $waiting_accept, 'autoapprove' => $autoapprove[0]->id));
        }
    }


    public function change_setting(Request $req){   //Change AutoApprove Settings
       $check = $req->input('approve');
       if($check == 'Enable AutoApprove'){  //Enabling AutoApprove
           DB::update('update settings set id=1');
       }
       else{    
           if($check=='Disable AutoApprove'){  //Disable AutoApprove
            DB::update('update settings set id=0');
            }
        }
        $autoapprove = Setting::get();
        $waiting_accept = User::where('accepted', 0)->get();
        return view('admin')->with(array('waiting_users' => $waiting_accept, 'autoapprove' => $autoapprove[0]->id));
    }
}