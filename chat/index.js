var http = require('http');
var util = require("util");

var request = require('request');

var Client = require('./client');

var apiUrl = 'http://localhost/resources/chat/chat';

var server = http.createServer(function (req, rep) {

});

var io = require('socket.io').listen(server);

var chat = io.of('/chat');

var clients = {};

chat.on('connection', function (socket) {

  console.log('new connection');

  socket.on('disconnect', function () {

    console.log('disconnection');
    var client = socket.clientCustom;
    if (typeof client !== 'undefined') {
      client.remove(socket);
      console.log('client', client);
      if (!client.isAlive()) {
        console.log('Le client ' + client.id + ' s\'est déconnecté');
        delete clients[client.id];
      }
    }
  });

  socket.on('new-client', function (e) {

    request.get(apiUrl + '/user?access_token=' + e.access_token, function (err, httpResponse, body) {
      console.log('identity : ', body);
      data = JSON.parse(body);
      var user = data.user;
      if (typeof clients[user.id] !== 'undefined') {
        var client = clients[user.id];
      } else {
        client = new Client(user.id);
        clients[user.id] = client;
      }

      client.push(socket);
      
      if(data.rooms) {
        for(var room of data.rooms){
          socket.join('Room #' + room.id);
        }
      }

      socket.emit('client-confirmed', data);

      console.log('client connectés : ', clients);

    });

  });

  socket.on('new-message', function (e) {

    request.post({
      url: apiUrl + '/push?access_token=' + e.access_token,
      form: {'content': e.message, 'room_id': e.room_id}
    }, function (err, httpResponse, body) {
      console.log('message received ', body);
      body = JSON.parse(body);
      chat.to('Room #' + body.room_id).emit('new-message', body);
    });

  });

  socket.on('delete-message', function (e) {

    request.post({
      url: apiUrl + '/delete' + e.message_id + '?access_token=' + e.access_token,
      form: {'content': e.message, 'room_id': e.room_id}
    }, function (err, httpResponse, body) {
      console.log('message received ', body);
      body = JSON.parse(body);
      chat.to('Room #' + body.room_id).emit('deleted-message', body);
    });

  });

  socket.on('request-room', function (e) {
    var toId = typeof e.to_id != 'undefined' ? '/' + e.to_id : '';
    request.get(apiUrl + '/requestRoom' + toId + '?access_token=' + e.access_token,
      function (err, httpResponse, body) {
        body = JSON.parse(body);
        socket.clientCustom.join('Room #' + body.id);
        socket.emit('new-room', body);
        if (typeof e.to_id != 'undefined' && typeof clients[e.to_id] != 'undefined') {
          clients[e.to_id].join('Room #' + body.id);
        }
      });
  });

  socket.on('get-room', function (e) {
    var roomId = e.room_id;
    request.get(apiUrl + '/room/' + roomId + '?access_token=' + e.access_token,
      function (err, httpResponse, body) {
        body = JSON.parse(body);
        var room = body.room;
        socket.clientCustom.emit('new-room', room);
      });
  });

  socket.on('see-room', function (e) {
    var roomId = e.room_id;
    request.get(apiUrl + '/seeRoom/' + roomId + '?access_token=' + e.access_token,
      function (err, httpResponse, body) {
        chat.to('Room #' + roomId).emit('saw-room', roomId);
      });
  });

  socket.on('attach-room', function (e) {
    var roomId = e.room_id;
    request.get(apiUrl + '/attachRoom/' + roomId + '?access_token=' + e.access_token,
      function (err, httpResponse, body) {
        socket.clientCustom.emit('attached-room', roomId);
      });
  });

  socket.on('detach-room', function (e) {
    var roomId = e.room_id;
    request.get(apiUrl + '/detachRoom/' + roomId + '?access_token=' + e.access_token,
      function (err, httpResponse, body) {
        socket.clientCustom.emit('detached-room', roomId);
      });
  });

  socket.on('delete-room', function (e) {
    var roomId = e.room_id;
    request.get(apiUrl + '/deleteRoom/' + roomId + '?access_token=' + e.access_token,
      function (err, httpResponse, body) {
        socket.clientCustom.emit('delete-room', roomId);
      });
  });

  socket.on('invite-user', function (e) {
  });
});


server.listen(18080);