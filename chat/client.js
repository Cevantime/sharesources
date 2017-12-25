function Client(id) {
  this.sockets = {};
  this.id = id;
}

Client.prototype.push = function(socket) {
  this.sockets[socket.id] = socket;
  socket.clientCustom = this;
}

Client.prototype.remove = function(socket) {
  delete this.sockets[socket.id];
}

Client.prototype.getId = function() {
  return this.id;
}

Client.prototype.emit = function(event, data){
  this.sockets.forEach(function(socket){
    socket.emit(event, data);
  });
}

Client.prototype.join = function(room) {
  this.sockets.forEach(function(socket){
    socket.join(room);
  });
}

Client.prototype.isAlive = function(event, data) {
  return this.sockets.length > 0;
}

module.exports = Client;