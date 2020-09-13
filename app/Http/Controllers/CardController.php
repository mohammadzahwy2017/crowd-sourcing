<?php

namespace App\Http\Controllers;

use App\idea;
use App\Workshop;
use App\workshop_users;
use App\groups;
use App\Groupe;
use Illuminate\Http\Request;
Use App\card;
use Illuminate\Support\Facades\DB;
use App\User;
Use App\Notifications\TaskCompleted;
use Notification;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function start()
    {
        return view('Start');
    }

    public function send_idea_to_database(Request $req) //Send Users Idea To Database(saving it)
    {
        $this->validate($req, ['idea' => 'required']);
        $key = $req->input('key');
        $id = auth()->User()->id;
       if (idea::where(array('user_id' => $id, 'workshop_key' => $key))->first()) { //Check if user already submitted idea
            $stage =   Workshop::where('key',$key)->first()->shuffle_stage;
            if($stage == 0){ //if stage = 0 wait for monitour to start
                return view('wait')->with('data',"Already Submitted To This Page, Wait For Shuffel Stage to Begin.");
            }
            else{   //user submitted and shuffel started, start voting.
                $data   = Workshop::where('key', $key)->first();
                return $this->go_to_voting_page($id,$key);
            }
        }  
       else { //Register and save Users Idea Then Wait For shuffel
            $Idea = new idea();
            $Idea->user_id = $id;
            $Idea->workshop_key = $key;
            $Idea->idea = $req->input('idea');
            $Idea->save();
            $na = Workshop::where('key', $key)->first()->answerd;
            $na++;
            Workshop::where('key', $key)->update(array('answerd' => $na));
            $WorkshopMonitour = Workshop::where('key', $key)->first()->monitor;
            $users= User::find($WorkshopMonitour);
            $username = User::where('id',$id)->first()->name;
            Notification::send($users, new TaskCompleted($username.' Has Submitted His Idea'));
            return view('wait')->with('data',"Wait For Monitour to Start Shuffel");
        }
    } 

    public function getAnswer($key,$id){    //Get Idea to vote
        $stage = Workshop::where('key',$key)->first()->shuffle_stage;
        $Idea_id = DB::table('user_votes')->where(array('workshop_key'=> $key, 'user_id' => $id, 'stage' => $stage))->first()->idea_id;
        $Idea = Idea::where(array('workshop_key'=>$key,'id'=>$Idea_id))->first()->idea;
        return array('idea'=> $Idea,'id'=>$Idea_id);       
    }

    public function vote(Request $req){ //Add Grade For Idea
       $this->validate($req, ['grade' => 'required']);

        $id = auth()->User()->id;
        $workshop_key = $req->input('key');
        $idea_id = $req->input('idea_id');
        $grade = $req->input('grade');


        $shuffle_nb = Workshop::where('key',$workshop_key)->first()->shuffle_stage;
      
        if($shuffle_nb > 5) //Shuffel Ended check if user has been set into a group by monitour and show him his group, else wait
        {
            if(Groupe::where(array('workshop_key'=>$workshop_key,'user_id'=>$id))->first()){
                $my_idea_id = Groupe::where(array('workshop_key'=>$workshop_key,'user_id'=>$id))->first()->idea_id;
                $my_idea_idea = Idea::where(array('workshop_key'=>$workshop_key,'id'=>$my_idea_id))->first()->idea;                  
                return view('user_result')->with(array('my_given_idea'=>$my_idea_idea));               
            }
            else{
                return view('wait')->with('data', "Waiting For Monitour To Provide Group");
            }
        }
        $voted = DB::table('user_votes')->where(array('user_id'=>$id,'workshop_key'=>$workshop_key, 'stage'=> $shuffle_nb))->first()->voted;
        if($voted == 0){ //Check how many times the user has voted, and add the grade to old grade
            return $this->go_to_voting_page($id,$workshop_key);
        }
        else{
            return $this->go_to_vote($id,$workshop_key,$grade,$idea_id);
            //return view('wait')->with('data', "Already Voted For this Stage, Wait For Monitour To Start the Next Shuffel Stage");
        }
    }
    public function go_to_vote($id,$workshop_key,$grade,$idea_id){

        //CHECK
        if(DB::table('voted')->where(array('user' => $id, 'workshop' => $workshop_key, 'idea' => $idea_id))->first() != null){
            return $this->go_to_voting_page($id,$workshop_key);
        }
        $stage = Workshop::where('key',$workshop_key)->first()->shuffle_stage;
        DB::insert('insert into voted (user,workshop,idea,stage) values (?,?,?,?)', array($id,$workshop_key,$idea_id,$stage));
        $prevoius_grade = Idea::where('id',$idea_id)->first()->grade;
        $new_grade = $prevoius_grade+$grade;
        Idea::where('id',$idea_id)->update(array('grade'=>$new_grade));
        return $this->go_to_voting_page($id,$workshop_key);
    }


    public function go_to_voting_page($id,$workshop_key){
        
        $stage = Workshop::where('key',$workshop_key)->first()->shuffle_stage;
        if($stage > 5)     //Shuffel ended go to grouping...
        {
            if(Groupe::where(array('workshop_key'=>$workshop_key,'user_id'=>$id))->first()){
                $my_idea_id = Groupe::where(array('workshop_key'=>$workshop_key,'user_id'=>$id))->first()->idea_id;
                $my_idea_idea = Idea::where(array('workshop_key'=>$workshop_key,'id'=>$my_idea_id))->first()->idea;                  
                return view('user_result')->with(array('my_given_idea'=>$my_idea_idea));               
            }
            else{
                return view('wait')->with('data', "Waiting For Monitour To Provide Group");
            }
        }
        $voted = DB::table('user_votes')->where(array('user_id'=>$id,'workshop_key'=>$workshop_key, 'stage'=> $stage))->first()->voted; 
        if($voted == 0){
            DB::table('user_votes')->where(array('user_id'=>$id,'workshop_key'=>$workshop_key, 'stage'=> $stage))->update(['voted' => 1]);
            
            $WorkshopMonitour = Workshop::where('key', $workshop_key)->first()->monitor;
            $users= User::find($WorkshopMonitour);
            $username = User::where('id',$id)->first()->name;
            Notification::send($users, new TaskCompleted($username.'Has Submitted His Grade'));
            $result =$this->getAnswer($workshop_key,$id); //Get idea to vote for
            $idea = $result['idea'];
            $idea_id = $result['id'];
            $data   = Workshop::where('key', $workshop_key)->first();
            return view('vote')->with(array("data"=>$data,"answer"=>$idea,"idea_id"=>$idea_id)); //return and voting for it
        }
        else{
            if($stage ==  5)
                return view('wait')->with('data', "Waiting For Monitour To Provide Group");
            return view('wait')->with('data', "Wait For Shuffel the Next Shuffel Stage");
        }
    }
}
    