var app = require('express')();
var http = require('http').Server(app);
// const { instrument } = require("@socket.io/admin-ui");
var io = require('socket.io')(http , {
    cors: {
        origin: '*',
    }
});
var Redis=require('ioredis');
var redis=new Redis();
var users=[];

http.listen(8006,function (){
    console.log('Listening to port 8006');
});
redis.subscribe('send-notification',function (){
    console.log('subscribed to send-notification');
});

io.on('connection',function (socket){
    socket.on('user_connected',function (user_id){
     users[user_id]=socket.id;
        console.log(users);

    });
    socket.on("disconnect",function (){
        var i=users.indexOf(socket.id);
        users.splice(i,1,0);
        console.log(users);
    });


})

redis.on('message',function (channel,message){
    if (channel==='send-notification'){
        let users=message.data;
        console.log(message);


    }
});


