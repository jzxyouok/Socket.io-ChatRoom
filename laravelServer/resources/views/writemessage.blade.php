 @extends('app')

 @section('content')
 @include("chatScreen")
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
        send message "hiiii " to perticular person
        <script>

/*
        function function1(){
        alert("sending the ajax request..");
           var http = new XMLHttpRequest();
           var url = "/one2onejson";
           var params = {"message":"ratan"};
           http.open("POST", url, true);

           //Send the proper header information along with the request
           http.setRequestHeader("Content-type", "text/json");

           http.onreadystatechange = function() {//Call a function when the state changes.
               if(http.readyState == 4 && http.status == 200) {
                   alert("ajax completed response received" );
               }
           }
           http.send(params);
           }
           */


        </script>

        <!-- <button onclick="function1()"> click here</button> -->

           <form action="/one2one" method="POST" >
                               <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                   <input type="text" name="message" value="Hi How are you ">
                                   <input type="submit" value="send">
                               </form>



     </div>

 @endsection