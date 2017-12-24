var http = require('http');
var util = require("util");

var request = require('request');

var apiUrl = 'http://localhost/resources/chat/chat';

var server = http.createServer(function (req, rep) {

});

var io = require('socket.io').listen(server);

io.sockets.on('connection', function (socket) {
  socket.on('new-message', function (e) {

    request.post({
      url: apiUrl + '/add?access_token=' + e.access_token,
      form: {'content': e.message, 'room_id': e.room_id}
    }, function (err, httpResponse, body) {
      console.log(body);
    });

  });
});

server.listen(18080);