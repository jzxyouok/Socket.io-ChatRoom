@extends('app')
 @section('content')
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <script>

  var socket = io.connect('http://localhost:8890');

  setTimeout(function(){
  console.log("sending msg to larvel to inform nodeJs server .." + socket.id);
                                token = $('input[name="_token"]').val();

                                   $.ajax({
                                         method: "POST",
                                         url: '/informNodeIamLoggedIn',
                                         data: {_token: token, websocketId: socket.id}                                                            });
  },3000);

  function SendOneOnOne() {
             var msg= document.getElementById("inp100").value;
             var to= document.getElementById("inp101").value;
           //  alert(" Sendind one on one message.." + msg);

               // alert("fucntion2 called ");
                  // Get the token value from the hidden form
                  token = $('input[name="_token"]').val();
                  // Send the ajax request
                  $.ajax({
                      method: "POST",
                      url: '/one2onejson',
                      data: {_token: token, msg: msg ,to: to}
                  });

                  }


   function SendToAll() {
      var msg= document.getElementById("inp103").value;
                  token = $('input[name="_token"]').val();
                                   // Send the ajax request
                                   $.ajax({
                                       method: "POST",
                                       url: '/sendToAll',
                                       data: {_token: token, message: msg}
                                   });
                  }


      /* --------------------------- */

      function logMeOut() {

                $.ajax({
                     method: "POST",
                     url: '/logOutMe',
                     data:{_token: token},
                     success: function(data) {
                      alert("logout out response" + data);
                     }
                 });

            }
  </script>


  <div class="topBar">
     <span>Welcome {{$username}} </span>
   </div>
   <form action="/logOutMe" method="POST" >
   <input type="hidden" name="_token" value="{{ csrf_token() }}">

     <input type="submit" class ="logout" value="Logout">
   </form>
 @include('chatWindowInner')
  <div class="outerBox">
        <div class="innerBox  innerBox1">
        <div id="messages1" >
         <div class="one2one">
         <span> PRIVATE CHAT </span> </div>
        </div>
        </div>

        <div class="innerBox innerBox2">
           <div id="messages" >
             <div class="one2one"> <span> GROUP CHAT </span> </div>
           </div>
         </div>

         <div class="innerBox innerBox3"> 
          <div id="onlineUsers" >
           <div class="one2one"> <span>  ONLINE USERS </span>  </div>
          </div>
          </div>
          
          <br>
          <!-- row 2 --> 
          <div class="msgBox">
           <div id="messages1" >
           </div>
               <input  class="longInput" type="text" id="inp100" placeholder="Enter your message here !" >
               <input  readonly class="longInput" type="text" id="inp101" placeholder="to whome you want to send message" ><br>
               <button class="longButton" onclick="SendOneOnOne()"> send One on One </button>
          </div>

          <!-- send to all  msg box --> 
          <div class="msgBox">
           <div id="messages1" ></div>
               <input class="BigInput" type="text" id="inp103" placeholder="Enter your message here !" >
           <button class="longButton" onclick="SendToAll()"> send to every one </button> <br>
          </div>

          <!--online user refresh button -->

          <div class="msgBox">
           <div id="messages1" ></div>
               
               <button onclick="void(0)" class="disable longButton"> Refresh List</button>
          </div>

  </div> 

  <div>
  </div>
 @endsection