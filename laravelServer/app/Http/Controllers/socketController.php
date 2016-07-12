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
    		$redis = LRedis::connection();
    		$redis->publish('myevent',Request::input('message'));
    		//return response()->json(['name' => 'second', 'state' => 'second']);
    		return redirect('writemessage');
    	}

    	public function one2onejson(){
            		$redis = LRedis::connection();
            		$redis->publish('myevent',Request::input('message'));
            		return response()->json(['name' => 'second', 'state' => 'second']);
            		//return redirect('writemessage');
            	}

}


/*<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class socketController extends Controller
{
    //
}

*/

