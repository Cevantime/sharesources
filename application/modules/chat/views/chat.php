<style>
    .chat .chat-room-content {
        display: none;
    }
    .chat .chat-room-content.displayed {
        display: initial;
    }
</style>

<div class="chat chat-templates" style="display: none">
    <li class="chat chat-room">
        <div class="chat chat-room-name">{{chat_room_name}}</div>
        <div class="chat chat-room-content">
            <ul class="chat chat-room-messages"></ul>
            <form class="chat chat-form-message">
                <label for="chat chat-message">
                    Message
                </label>
                <input type="text" class="chat chat-message-content" name="chat chat-message-content"/>
                <input type="submit" name="chat chat-send-message" value="envoyer"/>
            </form> 
        </div>
    </li>

    <li class="chat chat-room-message {{author_class}}">
        <p>{{message.content}}</p>
    </li>

    <li class="chat chat-friend-suggestion"><a href="#">{{user.login}} {{user.email}}</a></div>
</div>
<div id="chat-search-friends">
    <form>
        <label>Rechercher</label>
        <input type="text" name="search-friend" id="search-friend"/>
        <ul id="friend-suggestion">
        </ul>
    </form>
</div>

<div id="chat-rooms"></div>

<script type="text/javascript" src="<?php echo base_url('assets/vendor/js/chat/cookie.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/vendor/js/chat/ajax.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/vendor/js/chat/socket.io.js') ?>"></script>
<script type="text/javascript">

(function () {
  let chatSocket = io('<?php echo $_SERVER['HTTP_HOST'] ?>:18080' + '/chat');

  let apiUrl = '<?php echo base_url('chat/chat') ?>';

  let token = getCookie('resources_chat_token');

  chatSocket.emit('new-client', {access_token: token});
  
  chatSocket.on('client-confirmed', init);

  function init(user) {

    let chatRooms = document.getElementById('chat-rooms');

    let templateChatRoom = document.querySelector('.chat-templates .chat-room');
    let templateChatRoomMessage = document.querySelector('.chat-templates .chat-room-message');
    let templateFriendSuggestion = document.querySelector('.chat-templates .chat-friend-suggestion');

    let userId = user.id;

    ajax.get(apiUrl + '/room', [], function (rep) {
      rep = JSON.parse(rep);

      if (rep && typeof rep.rooms !== 'undefined' && rep.rooms) {
        for (let room of rep.rooms) {
          let chatRoomEl = templateChatRoom.cloneNode(true);
          let innerHTML = chatRoomEl.innerHTML;

          innerHTML = innerHTML.replace('{{chat_room_name}}', '#conversation' + room.id);

          chatRoomEl.innerHTML = innerHTML;

          chatRooms.append(chatRoomEl);

          chatRoomEl.getElementsByClassName('chat-form-message')[0]
            .addEventListener('submit', function (e) {
              e.preventDefault();
              let inputMessage = this.getElementsByClassName('chat-message-content')[0];
              let message = inputMessage.value;
              if (message) {
                inputMessage.value = '';
                chatSocket.emit('new-message', {
                  message: message,
                  room_id: room.id,
                  access_token: token
                });
              }
            }, false);

          function displayRoom(e) {
            e.preventDefault();
            chatRoomEl.removeEventListener('click', displayRoom);
            chatRoomEl.getElementsByClassName('chat-room-content')[0].classList.toggle('displayed');
            ajax.get(apiUrl + '/room/' + room.id, [], function (rep) {
              rep = JSON.parse(rep);
              if (rep && typeof rep.room.messages != 'undefined' && rep.room.messages) {
                let messages = chatRoomEl.getElementsByClassName('chat-room-messages')[0];
                for (let message of rep.room.messages) {
                  let messageEl = templateChatRoomMessage.cloneNode(true);
                  let innerHTMLMessage = messageEl.innerHTML;
                  innerHTMLMessage = innerHTMLMessage.replace('{{message.content}}', message.content);
                  innerHTMLMessage = innerHTMLMessage.replace('{{author_class}}', userId == message.from_id ? 'is-self' : 'is-other');
                  messageEl.innerHTML = innerHTMLMessage;
                  messages.append(messageEl);
                }

              }
            });
          }

          chatRoomEl.addEventListener('click', displayRoom, false);
        }
      }
    });

    friendList = document.querySelector('#friend-suggestion');

    document.getElementById("search-friend").addEventListener('input', function () {
      ajax.get(apiUrl + '/friends?search=' + encodeURIComponent(this.value), [], function (rep) {
        friendList.innerHTML = '';
        rep = JSON.parse(rep);
        if (rep) {
          for (let user of rep) {
            let el = templateFriendSuggestion.cloneNode(true);
            let innerHTML = el.innerHTML;
            for (let userProp in user) {
              console.log('{{user.' + userProp + '}}');
              innerHTML = innerHTML.replace('{{user.' + userProp + '}}', user[userProp]);
            }
            el.innerHTML = innerHTML;
            friendList.append(el);
            let destId = user.id;
            console.log(destId);
            el.addEventListener('click', function () {
                chatSocket.emit('request-room', {
                    'to_id' : destId,
                    'access_token' : token
                });
            }, false);
          }
        }
      });
    });

  }
})();

</script>