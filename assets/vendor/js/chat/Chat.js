function Chat(chatServerUrl, chatApiUrl, element) {
  this.serverUrl = chatServerUrl;
  this.apiUrl = chatApiUrl;
  UIElement.call(element);
}

Chat.prototype = Object.create(UIElement.prototype);

Chat.prototype.constructor = Chat;

function Room(id, element) {
  this.data.id = id;
  UIElement.call(element);
}


Room.prototype = Object.create(UIElement.prototype);

Room.prototype.constructor = Chat;

function Message(id, element) {
  this.data.id = id;
  UIElement.call(element);
}

Message.prototype = Object.create(UIElement.prototype);

Message.prototype.constructor = Chat;