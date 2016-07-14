 @extends('app')

 @section('content')
 @include("chatScreen")
 <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div> Enter your user name : <input type=text id="myname"/> </div>

  <button onclick="addUser()"> connect me : websocket to Node server </button>
     <div class="container">
         <div class="row">
             <div class="col-md-10 col-md-offset-1">
                 <div class="panel panel-default">
                     <div class="panel-heading">Send message</div>
                     <form action="/sendmessage" method="POST" >
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <input type="text" name="message" >
                         <input type="submit" value="send">
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <div>
     <br>
      --------------------------------------------------------------
      <br>
        Send message to all via larvel server

        <br>
        <input type="text" id="inp100" placeholder="Enter your message here !" >
         <input type="text" id="inp101" placeholder="to whome you want to send message" ><br>
        <button onclick="function2()"> send ajax to Larvel server </button> <br>
        ----------------------------------------------------------------
      </div>
        

        <script>
         alert("getting executed");
           function function2() {
           var msg= document.getElementById("inp100").value;
           var to= document.getElementById("inp101").value;
           alert("sending.." + msg);

              alert("fucntion2 called ");
                // Get the token value from the hidden form
                token = $('input[name="_token"]').val();
                // Send the ajax request
                $.ajax({
                    method: "POST",
                    url: '/one2onejson',
                    data: {_token: token, msg: msg ,to: to}
                });

                }

          function addUser(){
                       alert("add User11")
                         var userName = document.getElementById("myname").value;
                         alert("my user name is "+ userName );
                        socket.emit("init",userName,socket.id);
                         alert(userName);

                       }
        </script>


        <!-- <button onclick="function1()"> click here</button> -->

           <form action="/one2one" method="POST" >
                               <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                   <input type="text" name="message" value="Hi How are you ">
                                   <input type="submit" value="send">
                               </form>
     </div>

 @endsection