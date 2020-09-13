<?php

namespace App\Http\Controllers;

use App\idea;
use Illuminate\Database\DetectsDeadlocks;
use Illuminate\Http\Request;
use App\Workshop;
use App\User;
use App\workshop_users;
use App\Groupe;
Use App\Notifications\TaskCompleted;
use Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CardController;


class WorkshopController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Key(){  //Generate a random Key
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
        $size = strlen( $chars ); 
        $str=''; 
        for( $i = 0; $i < 5; $i++ ) {  
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }
        return $str;
    }

    public function CreateWorkshop(Request $req){ //Create a new WorkShop
        $key = $this->key(); //Generate a new Key
        while(Workshop::where('key','=',$key)->exists()){ //Check if generated Key Exists
            $key = $this->key();
        }  
        return view('workshop_register')->with('key',$key);
    }
    
    public function LoginWorkshop(){ //Returns User To WorkShopLogin View
        return view('workshop_login');
    }

    public function join_workshop(Request $req) //Join Existing Workshop
    {
        $this->validate($req, [ //Key is Required
            'key' => 'required'
        ]);
        
        $key = $req->input('key');
        if (!(Workshop::where('key', $key)->first())){ //Check if Key is valid
            $var = 'Invalid WorkShop Key';
            return view('workshop_login')->with('error',$var);
        }

        $id = auth()->User()->id;

        if(($w=Workshop::where('key', $key)->first())->monitor == $id){ //Checking if user is Monitour 
            $start =   Workshop::where('key',$key)->first()->started;
            
            if($start == 0){ //Checks if WorkShop has not started
                return view('workshop')->with('workshop',$w);
            }
            else{   //WorkShop Started return to monitour view with Ideas of users if exists
                $answer_to_my_workshop =Idea::where('workshop_key',$key)->get();
                return view('monitor_start')->with(array('workshop'=>$w,'answers'=>$answer_to_my_workshop));
            }
        }
      
        if(Idea::where(array('workshop_key'=>$key,'user_id'=>$id))->first()){   //User is not monitour check if he joined before, if yes redirect to voting view
            return app('App\Http\Controllers\CardController')->go_to_voting_page($id,$key);
        }

        $np = Workshop::where('key', $key)->first()->nb_of_participated;
        $nu = Workshop::where('key', $key)->first()->nb_of_users;

        if (!workshop_users::where(array('participant' => $id, 'workshop' => $key))->first() && $nu > $np) { //User Joining for first time, check if he can enter        
            $np++;                                                                                               //Check for space.... 
            Workshop::where('key',$key)->update(array('nb_of_participated'=>$np));
            User::where('id',$id)->update(array('role'=>'participated'));
            $workshopUsers = new workshop_users();
            $workshopUsers->workshop=$key;
            $workshopUsers->participant = $id;
            $workshopUsers->save();
            
            $WorkshopMonitour = Workshop::where('key', $key)->first()->monitor;
            $users= User::find($WorkshopMonitour);
            $username = User::where('id',$id)->first()->name;
            Notification::send($users, new TaskCompleted($username.' Has Joined The Workshop'));

            $start = Workshop::where('key',$key)->first()->started;
            if($start == 0){
                return view('wait')->with('data', "Please Wait For Monitour to start Workshop");
            }
            else{
                $data=Workshop::where('key', $key)->first();
                return view('userStart')->with('data',$data);
            }
        }
        else{
            if(workshop_users::where(array('participant' => $id, 'workshop' => $key))->first()){ //User Exists in WorkShop, Check if workshop started
                $start = Workshop::where('key',$key)->first()->started;
                if($start == 0){
                    return view('wait')->with('data', "Already Registered to Workshop, Wait For Monitour to start");
                }
                else{
                    $data=Workshop::where('key', $key)->first();
                    return view('userStart')->with('data',$data);
                }
            }
            if($np>=$nu){
                return view('workshop_login')->with('error', "Workshop is Full");
            } //WorkShop Full User Cant Join
        }
    }
    

    //For Facilitator to create a workshop
  //=================================================================================================//
    public function enterWorkshop(Request $req)
    {
        $this->validate($req, [ 
            'title' => 'required',
            'nb_of_users' => 'required',
            'description' => 'required'
        ]);
        $id = auth()->User()->id;
        $key = $req->input('key');
        if(Workshop::where('monitor',$id)->first()!=null){
            if(($workshop = Workshop::where(array('monitor'=> $id,'key'=>$key))->first()) != null){
                return view('workshop')->with('workshop', $workshop);
            }else{
                return view('workshop_register')->with(array('key'=> $req->input('key'), 'errorM' => 'Cant Create WorkShop User is Already a monitour.'));
            }
        }
        if($req->input('nb_of_users') < 6){
            return view('workshop_register')->with(array('data' => 'Minimum Number Of Users is 6', 'key' => $req->input('key')));
        }


        if (Workshop::where(array('monitor'=>$id,'key'=>$key))->first()!=null){ //Checking if User key and hes Monitour
            $workshop = Workshop::where(array('monitor'=>$id,'key'=>$key))->first();
            return view('workshop')->with('workshop', $workshop); //Redirect him to Workshop View
        }

        $workshop = new Workshop();

        $workshop->title = $req->input('title');
        $workshop->nb_of_users = $req->input('nb_of_users');
        $workshop->body = $req->input('description');
        $workshop->monitor = $id;
        $workshop->key = $key;
        $workshop->save();
        User::where('id', $id)->update(array('role' => 'facilitator'));
        return view('workshop')->with('workshop', $workshop);
    }

    //Porviding Answears Fase:
  //=================================================================================================//
    public function Moniterstart(Request $req){
       $this->validate($req,['card'=>'required']);
       $key = $req->input('key');
       $ques = $req->input('card');
       $maxnumber = Workshop::where('key',$key)->first()->nb_of_users;
       $currentnumber = Workshop::where('key',$key)->first()->nb_of_participated;
       $workshop = Workshop::where('key',$key)->first();

       if($maxnumber!=$currentnumber){
           return view('workshop')->with(array('workshop' => $workshop, 'error' => 'Please Wait for all users to join before starting Workshop'));
       }
       Workshop::where('key',$key)->update(array('question'=>$ques));
       Workshop::where('key',$key)->update(array('started'=>1));
       $data = Workshop::where('key',$key)->first();
       $answer_to_my_workshop =Idea::where('workshop_key',$key)->get();
       
       $usersofWorkshop = workshop_users::where('workshop', $key)->get();
       foreach($usersofWorkshop as $user){
            $users= User::find($user->participant);
            Notification::send($users, new TaskCompleted("Please Refresh Page to Submit Idea"));
       }

       return view('monitor_start')->with(array('workshop'=>$data ,'answers'=> $answer_to_my_workshop ));

    }
  //=================================================================================================//
    public function beginshuffle(Request $req){ //Starting 5 stage shuffel
       
        $key = $req->input('key');
        $raw =  Workshop::where('key',$key)->first();
        $stage = $raw->shuffle_stage;
        $numberofusers= ($workshopUsers = workshop_users::where('workshop', $key)->inRandomOrder()->get())->count();

            if($stage >5) { //Already finished the 5 stages
                return $this->final_result($key);
            }
            if($stage == 0){ //Distribute the cards
                $numberofideas = ($workshopIdeas = Idea::where('workshop_key', $key)->inRandomOrder()->get())->count();
                if($numberofideas != $numberofusers){
                    $data = Workshop::where('key',$key)->first();
                    $answer_to_my_workshop =Idea::where('workshop_key',$key)->get();
                    return view('monitor_start')->with(array('workshop'=>$data ,'answers'=> $answer_to_my_workshop, 'error' => "Not All Ideas Have Been Submited."));
                }
                foreach($workshopUsers as $user){
                    $id = $user->participant;
                    $stageC = 0;
                    $workshopIdeas = Idea::where('workshop_key', $key)->inRandomOrder()->get();
                    foreach($workshopIdeas as $idea){
                        if($idea->user_id != $id && $stageC != 5){
                            $stageC++;
                            DB::insert('insert into user_votes (user_id,workshop_key,idea_id,stage) values (?,?,?,?)', array($id,$key,$idea->id,$stageC));
                        }
                    }
                }
                $stage++;
                Workshop::where('key',$key)->update(array('shuffle_stage'=>$stage));
                $usersofWorkshop = workshop_users::where('workshop', $key)->get();
                foreach($usersofWorkshop as $user){
                    $users= User::find($user->participant);
                    Notification::send($users, new TaskCompleted("Please Refresh Page to Submit Grade For Stage: ".$stage));
                }
                $data = Workshop::where('key',$key)->first();
                $answer_to_my_workshop =Idea::where('workshop_key',$key)->get();
                return view('monitor_start')->with(array('workshop'=>$data ,'answers'=> $answer_to_my_workshop ));
            }
            else{
                $numberofsubmitedvotes = DB::table('voted')->where(array('workshop'=> $key, 'stage' => $stage))->get()->count();
                if($numberofsubmitedvotes == $numberofusers){//Increment Stage
                    $stage++;
                    Workshop::where('key',$key)->update(array('shuffle_stage'=>$stage));
                    if($stage >5) { //Already finished the 5 stages
                        return $this->final_result($key); 
                    }
                    $usersofWorkshop = workshop_users::where('workshop', $key)->get();
                    foreach($usersofWorkshop as $user){
                        $users= User::find($user->participant);
                        Notification::send($users, new TaskCompleted("Please Refresh Page to Submit Grade For Stage: ".$stage));
                    }
                    $data = Workshop::where('key',$key)->first();
                    $answer_to_my_workshop =Idea::where('workshop_key',$key)->get();
                    return view('monitor_start')->with(array('workshop'=>$data ,'answers'=> $answer_to_my_workshop ));
                }
                else{
                    $data = Workshop::where('key',$key)->first();
                    $answer_to_my_workshop =Idea::where('workshop_key',$key)->get();
                    return view('monitor_start')->with(array('workshop'=>$data ,'answers'=> $answer_to_my_workshop, 'error' => "Not All Users Have Submited Their Grades."));
                }
            }
}
    //=================================================================================================//
    //Show The Top 3 Ideas
    //=================================================================================================//
    public function final_result($key){
        $users =User::get();
        $my_users = array();
        $c = 0;
        foreach($users as $keys=>$value){
            if( Idea::where(array('workshop_key'=>$key,'user_id'=>$value->id))->first()){
                if( !(Groupe::where(array('workshop_key'=>$key,'user_id'=>$value->id))->first())){
                    $my_users[$value->id]=$value; 
                }
            }
        } 
    $Top_3_ideas = Idea::where('workshop_key',$key)->orderBy('grade', 'desc')->limit(3)->get();
        return view('moitor_last_view')->with(array('users'=>$my_users,'ideas'=>$Top_3_ideas));
    }
    //=================================================================================================//
    //Distribute Users into 3 Groups (Top 3 Ideas)
    //=================================================================================================//

    public function distribution(Request $req)
    {
        $idea_id = $req->input('idea');
        $key = $req->input('workshop_key');
        $workshop_k = explode('/',$key);
        $workshop_key=$workshop_k[0];
        foreach($req->input('user') as $curr_user_id){
            $group = new Groupe();
            $group->user_id = $curr_user_id;
            $group->idea_id = $idea_id;
            $group->workshop_key = $workshop_key;
            $group->save();
            $users=User::find($curr_user_id);
            Notification::send($users, new TaskCompleted("Please Refresh Page to See Your Group"));
        }
        $users =User::get();
         $my_users = array();
        foreach($users as $keys=>$value){
            if( Idea::where(array('workshop_key'=>$workshop_key,'user_id'=>$value->id))->first()){
                if(!(Groupe::where(array('workshop_key'=>$workshop_key,'user_id'=>$value->id))->first())){
                    $my_users[$value->id]=$value; 
                }
            }
        }
        $Top_3_ideas = Idea::where('workshop_key',$workshop_key)->orderBy('grade', 'desc')->limit(3)->get();
        return view('moitor_last_view')->with(array('users'=>$my_users,'ideas'=>$Top_3_ideas));
    }


    public function finish(){
        $userid=auth()->user()->id;
        User::where('id',$userid)->update(array('role'=>'user'));
        return view('home');
    }

}
