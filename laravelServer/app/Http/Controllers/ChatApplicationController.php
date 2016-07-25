<?php namespace App\Http\Controllers;
use App\Http\Requests;
use Session;
use Request;
use LRedis;
use View;


class chatApplicationController extends Controller {


public function __construct()
	{
		$this->middleware('guest');
	}


public function AppLogin()
	{
		return view('AppLogin');
	}


public function chatScreen()
    	{
    	   $username = Session::get('username');
    	   // redirecting to index page if the session not present
    	   if(! $username){
    	    redirect("/");
    	   }


    	   $input = Request::all();

    	  // db call

    	   if(Request::input('username') == Request::input('password')){
    	      /* saving the session */
    	       Session::put('username',Request::input('username'));


             /* as there are no client connected at this point
             thats why the message is not processed by redis
              again, this is a assumption
              */
    	    // $redis->publish('message', "xxxxx");
    	       $res = array("username"=>Request::input('username'));
    	        //$data_json = json_encode($res);
    	        /* sending the event to queue so that node should read it */
    	       //  $redis->publish('AddToOnlineUsers',$data_json);

    	        /* going to next page with login user login name " */
    	        return View::make('chatWindow')->with($res);
    	      // $data = array("data"=>$data_json);
    	        //return view('chatWindow',$res); // so passing json

    	   }else{
    	         return redirect('/');  // need to specify  URL
    	   }


    	} // function chat screen completed


   public function informNodeIamLoggedIn() {
         $input = Request::all();
         $webSocket= Request::input('websocketId');
         $redis = LRedis::connection();
         $username = Session::get('username');
         $res = json_encode(array("username"=>$username,"websocketId"=>$webSocket));
         $redis->publish('AddToOnlineUsers', $res);
         return response()->json(['status' => '200','msg'=>'event "AddToOnlineUsers" published to redis']);

   }


   	public function sendToAll(){
   		$redis = LRedis::connection();
   		$res = json_encode(array("msg"=>Request::input('message'),"from"=>Session::get('username')));
   		$redis->publish('message', $res);
   		return response()->json(['status' => '200', 'msg' => 'published to every online connection']);

   	}

   	public function logOutMe(){
       		//$redis = LRedis::connection();
       		//$redis->publish('logOutMe', Session::get('websocketId'));
       		//return response()->json(['status' => '200', 'msg' => ' logMeOut published to Node Server']);
             return redirect('/');
             //return view('AppLogin');
       	}




} // end of controller

