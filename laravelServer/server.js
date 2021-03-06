/**
 * Created by ratan_000 on 7/15/2016.
 */
/**
 * Created by ratan_000 on 7/11/2016.
 */

var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');


console.log("server started on 8890..");
server.listen(8890);

var conn=[];
var map={};
var revmap={};
var AuthenticatedUsers=[];
var total=[];
var socket;

var redisClient = redis.createClient();
redisClient.subscribe('message');
redisClient.subscribe('myevent');
redisClient.subscribe('myevent1');
redisClient.subscribe('login');
redisClient.subscribe('logout');
redisClient.subscribe('logOutMe');
redisClient.subscribe('AddToOnlineUsers');


redisClient.on("message", function(channel, message) {
    console.log("(((((((()))))))))))");
    console.log("mew message in queue " + message + "channel:" + channel);

    if(channel == "myevent") {
        /* this is for one to one message */
        console.log("emitting perticular socket using io.sockets");
        //socket.broadcast.to(conn[1]).emit(channel,message);
        var allJson = JSON.parse(message);
        console.log("from received json: msg=" + allJson.msg);
        console.log("from received json: to=" + allJson.to);
        // lookup in map to get the maps
        var msg = allJson.msg;
        var date = new Date();
        date= date.toString();
        console.log("kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk");
        date = date.split(' '); // on space
        var curr_time = date[1]+ " " + date[2]+ " " + date[3] + " " + date[4];
        msg=allJson.from + " : " + msg + "      ..." + curr_time.toLocaleLowerCase();
        var tosendSocketId = map[allJson.to];
        console.log(tosendSocketId);
       // tosendSocketId = '/#' + tosendSocketId;
         tosendSocketId = tosendSocketId;
        console.log("sending msg:" + msg + " to:" + allJson.to + ": " + tosendSocketId);
        console.log(conn);
        console.log("channel is :" + channel);
        /* channel that we are sendind is myevent */
        // io.sockets.broadcast.to(tosendSocketId).emit(channel,msg); won't work
        var senderSocketId= map[allJson.from];
        console.log("***(((((***** sender socketId:"+ allJson.from + ":" +senderSocketId);
        if(tosendSocketId){
            io.sockets.connected[tosendSocketId].emit(channel, msg);
            io.sockets.connected[senderSocketId].emit(channel, msg);
        }else{
             console.log("tosendSocketId is: " + tosendSocketId )
        }

    } else if (channel == "logout") {
        var allJson = JSON.parse(message);
        io.sockets.emit("kadamEvent", "some one logged out: " + allJson.userName);
        // some user has logout and server is reporting us
        // remove him from online people list

    } else if (channel == "login") {
        var allJson = JSON.parse(message);
        io.sockets.emit("kadamEvent", "some one joined" + allJson.userName);
        // some user has logged in try to put in logged in user
    } else if (channel == "AddToOnlineUsers") {
        console.log(" invoked the AddToOnlineUsers event" + message);
        var res = JSON.parse(message);
        //console.log(res);
        console.log("sending to all the clients");
        io.sockets.emit("NewOnlineUser",res.username);
        var socketId= "/#"+res.websocketId;
        map[res.username] =socketId;  // here message is the username send by Larvel server
        revmap[socketId]=res.username; // maintaining the reverse mapping which will get
        // useful the disconnected client in 0(1);
        console.log(map);
    }else if (channel == "RemoveFromOnlineUsers") {
        console.log(" invoked the AddToOnlineUsers event" + message);
        var res = JSON.parse(message);
        //console.log(res);
        console.log("sending to the clients");
        io.sockets.emit("NewOnlineUser",allJson);
        var socketId= "/#"+res.websocketId;
        map[res.username] =socketId;  // here message is the username send by Larvel server
        console.log(map);
    } else if(channel == "logOutMe"){
          console.log("^^^^ User wants to get logged out ! ");
          console.log("message: websocketId "  + message);
          var userName =revmap[message];
          delete map[userName];
          delete revmap[message];
          console.log(map);
         console.log(revmap);

    } else {
        console.log("emitting to all using  io.sockets::");
        var msg = JSON.parse(message);

        var date = new Date();
        date= date.toString();
        date = date.split(' '); // on space
        var curr_time = date[1]+ " " + date[2]+ " " + date[3] + " " + date[4];
         var newmsg= msg.from+ " : " + msg.msg  + "      ..."  + curr_time.toLocaleLowerCase();

        io.sockets.emit(channel, newmsg);

        console.log("continue");

    }
    /*************/
});
    // json parse take out the to : & message
// socket.on is to listen the client
// to get anything from redis we have only one method redis.on("message");
// from the channel/ event name we have ro execute perticular code.
io.on('connection', function (socket) {

    console.log("new client connected" + socket.id);
    total.push(socket.id);
    conn.push(socket.id);
    console.log(conn);

    // now connection has establish - we can play with the socket events
    socket.on("getAllonlineUsers",function(){
        console.log("###########3sending the entire list..");
        var userArr=Object.keys(map);
        console.log("all arrays:");
        console.log(userArr);
        socket.emit("getAllonlineUsers",userArr);

    });

    socket.on("init",function(username,socketid){
        console.log("user :" + username + " sends his identity. with socket id:" + socketid);
        map[username]= socketid;
        console.log("type of socket id is" + typeof(socketid));
        console.log("your socket id is ");
        console.log(""+socketid);
        console.log(JSON.stringify(map));
    });

    redisClient.on("message1", function(channel, message) {

        console.log("(((((((()))))))))))");
        console.log("mew message in queue "+ message + "channel:" + channel);
        // json parse take out the to : & message

        if(channel == "myevent"){
            //socket.broadcast.to(conn[1]).emit(channel,message);
            var allJson = JSON.parse(message);
            console.log("from received json: msg=" + allJson.msg);
            console.log("from received json: to=" + allJson.to);
            // lookup in map to get the maps
            var msg= allJson.msg;
            var tosendSocketId= map[allJson.to];
            console.log(tosendSocketId);
            tosendSocketId = '/#' + tosendSocketId;
            console.log("sending msg:" + msg + " to:" + allJson.to+ ": " +tosendSocketId);
            console.log(conn);
            /* channel that we are sendind is myevent */

            io.sockets.connected[tosendSocketId].emit(channel, msg);
           // socket.broadcast.to(tosendSocketId).emit(channel,msg);

        }else if(channel == "logout"){
            var allJson = JSON.parse(message);
            io.sockets.emit("kadamEvent","some one logged out: " +  allJson.userName);
            // some user has logout and server is reporting us
            // remove him from online people list

        }else if(channel =="login") {
            var allJson = JSON.parse(message);
            io.sockets.emit("kadamEvent","some one joined" +  allJson.userName);
            // some user has logged in try to put in logged in user
        }else if(channel == "AddToOnlineUsers") {
            console.log(" invoked the AddToOnlineUsers event :" + message);
        }else{
            console.log("sending messsage to every open connection denied..");
          //  socket.emit(channel, message);
        }

    });
    // below code won't work
    redisClient.on("myevent", function() {
        console.log("**** myEvent called *****");
        console.log("****from my event in queue "+ "channel:" );
        //  socket.emit(channel, message);
    });

    socket.on('disconnect', function() {
        console.log("client disconnected.." + socket.id);
        console.log("sent event all client to update updated user list");
        console.log(conn);

         user=revmap[socket.id];
        console.log("disconnected user:" + user);

        io.sockets.emit("OnlineUserDisconnected",user);
        //
        delete map[user];
        delete revmap[socket.id];
        /////
        redisClient.removeListener('message',function(){
           console.log("removed listener");
        });
    });

    redisClient.on('error',function(){
        console.log("error found !");
    })


});
