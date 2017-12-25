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
      if (typeof clients[data.id] !== 'undefined') {
        var client = clients[data.id];
      } else {
        client = new Client(data.id);
        clients[data.id] = client;
      }

      client.push(socket);

      socket.emit('client-confirmed', {
        id: client.id
      });

      console.log('client connectés : ', clients);

    });

  });

  socket.on('new-message', function (e) {

    request.post({
      url: apiUrl + '/add?access_token=' + e.access_token,
      form: {'content': e.message, 'room_id': e.room_id}
    }, function (err, httpResponse, body) {
      console.log('message received ', body);
      chat.to('Room #'+body.room_id).emit('new-message', body);
    });

  });

  socket.on('request-room', function (e) {
    var toId = typeof e.to_id != 'undefined' ? '/' + e.to_id : '';
    request.get(apiUrl + '/createRoom' + toId + '?access_token=' + e.access_token,
      function (err, httpResponse, body) {
        socket.clientCustom.join('Room #'+room_id);
        if(typeof e.to_id != 'undefined' && typeof clients[to_id] != 'undefined') {
          client[to_id].join('Room #'+room_id);
        }
      });
  })
});


server.listen(18080);