<!--
   // included in the chat Window Blade
<div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2" >
            All msg
               ************* group message**********
               *************************************
              <div id="messages" ></div>
              ********* one on one messages *********
              ***************************************
                <div id="messages1" ></div>
                ********online users *****************
                <div id="onlineUsers" ></div>
            </div>
        </div>
    </div>

    -->

     <script>

        var justLoggedIn = false;

          if(!justLoggedIn){
               justLoggedIn=true;
               socket.emit("getAllonlineUsers","loggedInjustNow");
               }

               socket.on("getAllonlineUsers",function(data) {

                    console.log("online users");
                    console.log(data);

                    for(var i=0; i < data.length ; i++){

                        $( "#onlineUsers" ).append( "<div name="+data[i]+">"+ data[i]+"</div>" );
                   }

               });



            socket.on('message', function (data) {
                $( "#messages" ).append( "<p>"+data+"</p>" );
                console.log("this is data :" + data);
              });

            socket.on('myevent', function (data) {
                              $( "#messages1" ).append( "<p>"+data+"</p>" );
                              console.log("one to one message:" + data);
                            });

            socket.on('NewOnlineUser', function (data) {
                              $( "#onlineUsers" ).append( "<div name="+data+">"+data+"</div>" );
                            });

            socket.on('OnlineUserDisconnected', function (data) {
                                                    // $( "#messages1" ).append( "<p>"+data+"</p>" );
                        // alert("online user disconnected :" + data);
                         $("[name="+ data +"]").remove();
                             });


        $(document).ready(function() {

        var onlineUsers=document.getElementById("onlineUsers");
        var myInput=document.getElementById("inp101");

                      onlineUsers.addEventListener("click",selectUserToChat);
              //  $("#onlineUsers").on("click",selectUserToChat);

                      function selectUserToChat(e) {
                                   myInput.value=e.target.innerText;
                                }
        });




     </script>