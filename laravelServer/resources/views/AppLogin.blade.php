@extends('app')

@section('content')
 <input type="hidden" name="_token" value="{{ csrf_token() }}">

<form action="/chatScreen" method="POST" >
                               <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div>  Login Screen </div>

                                   <input type="text" name="username" value="Ratan">
                                   <input type="password" name="password" value="Ratan">
                                   <input type="submit" value="send">
                               </form>
 @endsection