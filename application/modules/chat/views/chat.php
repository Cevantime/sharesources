<div class="chat-templates" style="display: none">
    <li class="chat-room">
        <div class="chat-room-name">{{chat_room_name}}</div>
        <div class="chat-room-content">
            <ul class="chat-room-messages"></ul>
            <form class="chat-form-message">
                <label for="chat-message">
                    Message
                </label>
                <input type="text" class="chat-message-content" name="chat-message-content"/>
                <input type="submit" name="chat-send-message" value="envoyer"/>
            </form> 
        </div>
    </li>

    <li class="chat-room-message {{author_class}}">
        <p>{{message.content}}</p>
    </li>

    <li class="chat-friend-suggestion"><a href="#">{{user.login}} {{user.email}}</a></div>
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

  var socket = io.connect('<?php echo $_SERVER['HTTP_HOST'] ?>:18080');
  var apiUrl = '<?php echo base_url('chat/chat') ?>';

  var chatRooms = document.getElementById('chat-rooms');

  var token = getCookie('resources_chat_token');

  var templateChatRoom = document.querySelector('.chat-templates .chat-room');
  var templateChatRoomMessage = document.querySelector('.chat-templates .chat-room-message');
  var templateFriendSuggestion = document.querySelector('.chat-templates .chat-friend-suggestion');

  var selectedFriend = null;

  var userId = getCookie('resources_user_id');

  ajax.get(apiUrl + '/room', [], function (rep) {
    var rep = JSON.parse(rep);

    if (rep && typeof rep.rooms !== 'undefined') {
      for (room of rep.rooms) {
        var chatRoomEl = templateChatRoom.cloneNode(true);
        var innerHTML = chatRoomEl.innerHTML;

        innerHTML = innerHTML.replace('{{chat_room_name}}', '#conversation' + room.id);

        chatRoomEl.innerHTML = innerHTML;

        chatRooms.append(chatRoomEl);

        chatRoomEl.getElementsByClassName('chat-form-message')[0]
          .addEventListener('submit', function (e) {
            e.preventDefault();
            var inputMessage = this.getElementsByClassName('chat-message-content')[0];
            var message = inputMessage.value;
            if (message) {
              inputMessage.value = '';
              socket.emit('new-message', {
                message: message,
                room_id: room.id,
                access_token: token
              });
            }
          });

        function clickListen(e) {
          e.preventDefault();
          chatRoomEl.removeEventListener('click', clickListen);
          ajax.get(apiUrl + '/room/' + room.id, [], function (rep) {
            rep = JSON.parse(rep);
            if (rep && typeof rep.room.messages != 'undefined') {
              var messages = chatRoomEl.getElementsByClassName('chat-room-messages')[0];
              for (message of rep.room.messages) {
                var messageEl = templateChatRoomMessage.cloneNode(true);
                var innerHTMLMessage = messageEl.innerHTML;
                innerHTMLMessage = innerHTMLMessage.replace('{{message.content}}', message.content);
                innerHTMLMessage = innerHTMLMessage.replace('{{author_class}}', userId == message.from_id ? 'is-self' : 'is-other');
                messageEl.innerHTML = innerHTMLMessage;
                messages.append(messageEl);
              }

            }
          });
        }

        chatRoomEl.addEventListener('click', clickListen);
      }
    }
  });


  friendList = document.querySelector('#friend-suggestion');

  document.getElementById("search-friend").addEventListener('input', function () {
    ajax.get(apiUrl + '/friends?search=' + encodeURIComponent(this.value), [], function (rep) {
      friendList.innerHTML = '';
      rep = JSON.parse(rep);
      if (rep) {
        for (user of rep) {
          var el = templateFriendSuggestion.cloneNode(true);
          var innerHTML = el.innerHTML;
          for (userProp in user) {
            console.log('{{user.' + userProp + '}}');
            innerHTML = innerHTML.replace('{{user.' + userProp + '}}', user[userProp]);
          }
          el.innerHTML = innerHTML;
          friendList.append(el);
          el.addEventListener('click', function () {
            selectedFriend = user.id;
          });
        }
      }
    });
  });

})();

</script>