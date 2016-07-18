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


console.log("server started on 8890");
server.listen(8890);

var conn=[];
var map={};
var total=[];
var socket;
// socket.on is to listen the client
// to get anything from redis we have only one method redis.on("message");
// from the channel/ event name we have ro execute perticular code.
io.on('connection', function (socket) {

    console.log("new client connected" + socket.id);
    total.push(socket.id);
    conn.push(socket.id);
    console.log(conn);
    // console.log(conn);
    var redisClient = redis.createClient();
    redisClient.subscribe('message');
    redisClient.subscribe('myevent');
    redisClient.subscribe('myevent1');
    redisClient.subscribe('login');
    redisClient.subscribe('logout');

    /*
     setInterval(function(){
     io.sockets.emit('kadamEvent',"kadam data");
     console.log("sent event..");
     },5000);

     */

    // now connection has establish - we can play with the socket events
    socket.on("init",function(username,socketid){
        console.log("user :" + username + " sends his identity. with socket id:" + socketid);
        map[username]= socketid;
        console.log("type of socket id is" + typeof(socketid));
        console.log("your socket id is ");
        console.log(""+socketid);
        console.log(JSON.stringify(map));
    });

    redisClient.on("message", function(channel, message) {

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
            socket.broadcast.to(tosendSocketId).emit(channel,msg);

        }else if(channel == "logout"){
            var allJson = JSON.parse(message);
            io.sockets.emit("kadamEvent","some one logged out: " +  allJson.userName);
            // some user has logout and server is reporting us
            // remove him from online people list

        }else if(channel =="login") {
            var allJson = JSON.parse(message);
            io.sockets.emit("kadamEvent","some one joined" +  allJson.userName);
            // some user has logged in try to put in logged in user
        }else{
            socket.emit(channel, message);
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
        // update laravel
        // update client to remove the disconnected client from client list
        io.sockets.emit('kadamEvent', {"disconnected_socket_id" : socket.id});
        console.log("sent event all client to update updated user list");
        console.log(conn);
        //console.log()
        //conn[0].disconnect; // this can be called from client only */
        /* below is just the event actual disconnect will take place at client */
        //io.sockets.emit('kadamEvent', {"force_disconnected_socket_id" : force});

        redisClient.quit();
    });


});



