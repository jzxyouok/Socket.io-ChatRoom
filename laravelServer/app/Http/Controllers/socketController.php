<?php namespace App\Http\Controllers;
use App\Http\Requests;
//use App\Http\Controllers\Controller;
//use App\Http\Controllers\Session;
use Session;
use Request;
use LRedis;


class SocketController extends Controller {
	public function __construct()
	{
		$this->middleware('guest');
	}


	public function index()
	{
		return view('socket');
	}

	public function writemessage()
	{
		return view('writemessage');
	}

	public function sendMessage(){
		$redis = LRedis::connection();
		$redis->publish('message', Request::input('message'));
		//return response()->json(['name' => 'second', 'state' => 'second']);
		 return redirect('writemessage');
	}

	public function one2one(){
	      //  $input = Input::all();


    		$redis = LRedis::connection();
    		$redis->publish('myevent',input('name'));
    		//return response()->json(['name' => 'second', 'state' => 'second']);
    		return redirect('writemessage');
    	}
// Request $input
    	public function one2onejson(){
    	           // console.log("data" +  data);
    	          $input = Request::all();
    	          $res =array('msg'=>Request::input('msg'),'to'=>Request::input('to'));
    	          $res_json = json_encode($res);


    	          //  dd($input);
            		$redis = LRedis::connection();
            		//$redis->publish('myevent',Request::input('msg'));
            		$redis->publish('myevent',$res_json);  // publish second parametre should be str / json
            		return response()->json(['status' => '200']);
            		//return redirect('writemessage');
            	}

            	/////////////////////////////////// Session::put('key', 'value');
            	public function HandleloginJson(){
            	//  $request->session()->put(["Ratan_session"=>"ratan_session_value"]);
                    	            $input = Request::all();
                    	           // dd($request->all());
                                  // db call to verify the deatils
                                  // if good then setup the session by setting the header & send the header 200
                                   // Session::put('usename',"ratanSesion");
                                   Session::put('usename',Request::input('userName'));
                                   $redis = LRedis::connection();
                                   // publishling the login event so that Node server add this user to logged in user;
                                   $res = json_encode(array("userName"=>Request::input('userName')));
                                  // $res = json_encode();
                                   $redis->publish('login',$res);
                            	   return response()->json(['status' => '200','message' =>"login Successfull Session Established"]);
                            	}

               ////////////////////////////////////////////
               public function HandlelogoutJson(){
                             	            $input = Request::all();
                             	             $redis = LRedis::connection();
                             	             // putting the logout event to Radis so that Node JS server should read it and it
                             	             // have to send message to client that user get disconnected
                             	             $value = Session::get('userName');
                             	             $res = json_encode(array("userName"=>$value));
                             	             $redis->publish('logout',$res);
                             	            // get out of the session
                             	            Session::flush();
                             	           // dd(Session::all());
                                     		return response()->json(['status' => '200','message' =>"logged out Successfull Session Ended"]);
                                     		//return redirect('writemessage');
                                     	}

}
