(function () {

  chatSocket.emit('new-client', {'access_token': token});
  chatSocket.on('client-confirmed', init);


  function init(data) {
    var user = data.user;
    var initialRooms = data.rooms || [];

    initialRooms.forEach(function (r) {
      r.displayed = false;
      r.hasBeenUpdated = false;
      r.messages = [];
      r.users = [];
    });

    Vue.component('attached-room', {
      props: ['room'],
      template: '#chat-room-template',
      data: function () {
        var self = this;
        return {
          message: ''
        }
      },

      methods: {
        toogleDisplayed: function (event) {
          this.$emit('room-change-display', {'room_id': this.room.id, 'displayed': !this.room.displayed});
          this.scrollTop();
        },

        addMessage: function (event) {
          chatSocket.emit('new-message', {
            access_token: token,
            message: this.message,
            room_id: this.room.id
          });

          this.message = '';
        },
        scrollTop: function () {
          var self = this;
          setTimeout(function () {
            var container = self.$refs.container;
            if (typeof container != 'undefined') {
              container.scrollTop = container.scrollHeight;
            }
          }, 100);
        },
        detach: function (e) {
          e.stopPropagation();
          chatSocket.emit('detach-room', {access_token: token, 'room_id': this.room.id});
        }
      },

      computed: {
        name() {
          return this.room.users
            .filter(function (u) {
              return u.id != user.id
            })
            .map(function (u) {
              return u.login
            }).join(', ');
        }
      },

      mounted: function () {
        
        chatSocket.emit('get-room', {'access_token': token, room_id: this.room.id});


      }
    });

    Vue.component('message', {
      props: ['message'],
      template: '#chat-message-template',
      computed: {
        isSelf: function () {
          return this.message.from_id == user.id
        }
      }
    });


    var chat = new Vue({
      el: '#chat',

      data: {
        friendSuggestions: [],
        rooms: initialRooms,
        user: user,
        searched: '',
        searchDisplayed: false
      },

      methods: {
        search: function (event) {
          var self = this;
          if (self.searched.length < 2) {
            return;
          }
          fetch(apiUrl + "/friends?access_token=" + token + '&search=' + self.searched)
            .then(function (rep) {
              return rep.json();
            })
            .then(function (rep) {
              self.friendSuggestions = rep;
              return rep;
            })
        },

        displaySearch: function () {
          this.searchDisplayed = true;
        },
        hideSearch: function () {
          this.searchDisplayed = false;
        },
        roomChangeDisplay: function (e) {
          var room = this.rooms.find(function (r) {
            return e.room_id == r.id;
          });
          if (room) {
            room.displayed = e.displayed;
            if (e.displayed) {
              room.hasBeenUpdated = false;
              chatSocket.emit('see-room', {access_token: token, 'room_id': room.id});
            }
          }

        },
        select: function (friend) {
          var room = this.rooms.find(function (r) {
            return r.to_id == friend.id || r.author_id == friend.id;
          });
          if (room) {
            room.is_room_attached = true;
            room.displayed = true;
            chatSocket.emit('see-room', {access_token: token, 'room_id': room.id});
            chatSocket.emit('attach-room', {access_token: token, 'room_id': room.id});
          } else {
            chatSocket.emit('request-room', {'access_token': token, to_id: friend.id});
          }
        }
      },
      computed: {
        attachedRooms() {
          return this.rooms.filter(function (room) {
            return room.is_room_attached != false && room.is_room_attached != null;
          });
        }
      },
      created() {
        document.getElementById('chat').style.display = 'flex';

      },
      mounted() {
        var self = this;

        chatSocket.on('new-room', function (data) {
          var room = self.rooms.find(function (r) {
            return data.id == r.id;
          });
          if (!room) {
            room = data;
            room.displayed = true;
            room.is_room_attached = true;
            room.hasBeenUpdated = true;
            self.rooms.push(room);
            chatSocket.emit('attach-room', {access_token: token, 'room_id': room.id});
          } else {
            room.messages = data.messages.reverse();
            room.users = data.users;
          }
        });

        chatSocket.on('new-message', function (data) {
          
          var room = self.rooms.find(function (r) {
            return data.room_id == r.id;
          });
          
          if (!room) {
            self.rooms.push({
              is_room_attached: true,
              displayed: false,
              hasBeenUpdated: true,
              id: data.room_id,
              messages: [],
              users: []
            });
//            console.log('room', data);
            chatSocket.emit('attach-room', {access_token: token, 'room_id': data.room_id,});
          } else {
            if (!room.is_room_attached || room.is_room_attached == 0) {
              room.is_room_attached = true;
              chatSocket.emit('attach-room', {access_token: token, 'room_id': room.id});
            }
            if (!room.displayed) {
              room.hasBeenUpdated = true;
            }
            room.messages.push(data);
            if(typeof self.$refs['roomComp'] != 'undefined' ) {
              var roomComp = self.$refs['roomComp'].find(function(rc){return rc.room.id == room.id});
              if(typeof roomComp !== 'undefined'){
                roomComp.scrollTop();
              }
            }
          }
        });

        chatSocket.on('detached-room', function (data) {

          var room = self.attachedRooms.find(function (r) {
            return data == r.id;
          });
          if (room) {
            room.displayed = false;
            room.is_room_attached = false;
          }
        });
      }
    });
  }
})();