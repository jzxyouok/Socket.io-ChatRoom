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
io.on('connection', function (socket) {
    console.log("new client connected" + socket.id);
   conn.push(socket.id);
    console.log(conn);
    var redisClient = redis.createClient();
    redisClient.subscribe('message');
    redisClient.subscribe('myevent');

    redisClient.on("message", function(channel, message) {

         console.log("(((((((()))))))))))");
        console.log("mew message in queue "+ message + "channel:" + channel);
        if(channel == "myevent"){
            socket.broadcast.to(conn[1]).emit(channel,message);
        }else{
            socket.emit(channel, message);
        }



    });


    redisClient.on("myevent", function() {
        console.log("**** myEvent called *****");
        console.log("****from my event in queue "+ "channel:" );
      //  socket.emit(channel, message);
    });



    socket.on('disconnect', function() {
        console.log("client disconnected.." + socket.id);
        redisClient.quit();
    });


});


