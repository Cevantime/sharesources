function Client(id) {
  this.sockets = {};
  this.id = id;
}

Client.prototype.push = function(socket) {
  this.sockets[socket.id] = socket;
  socket.clientCustom = this;
  return this;
}

Client.prototype.remove = function(socket) {
  delete this.sockets[socket.id];
  return this;
}

Client.prototype.getId = function() {
  return this.id;
}

Client.prototype.emit = function(event, data){
  for(sockKey in this.sockets){
      this.sockets[sockKey].emit(event, data);
  }
  return this;
}

Client.prototype.join = function(room) {
  for(sockKey in this.sockets){
    this.sockets[sockKey].join(room);
  }
  
  console.log('Le client '+this.id+' a rejoint la Room #'+room);
  return this;
}

Client.prototype.isAlive = function(event, data) {
  
  return Object.keys(this.sockets).length > 0;
}

module.exports = Client;