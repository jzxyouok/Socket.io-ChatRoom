<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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
    	            // var_dump($input);
    	          //  dd($input);
    	          $res =array('msg'=>Request::input('msg'),'to'=>Request::input('to'));
    	         // dd($res);
    	           // $myMap = json_encode(i)

    	           $res_json = json_encode($res);


    	          //  dd($input);
            		$redis = LRedis::connection();
            		//$redis->publish('myevent',Request::input('msg'));
            		$redis->publish('myevent',$res_json);  // publish second parametre should be str / json
            		return response()->json(['status' => '200']);
            		//return redirect('writemessage');
            	}

}



