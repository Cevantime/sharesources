<html>
    <head>
        <title>Tchat</title>

        <link href="<?php echo base_url("assets/local/css/chat/chat.css"); ?>" rel="stylesheet" />
    </head>
    <body>
        <div id="chat-templates" style="display: none">
            <div id="chat-room-template">
                <li class="chat chat-room">
                    <div class="chat chat-room-name" v-bind:class="{ 'has-been-updated': room.hasBeenUpdated }" v-on:click="toogleDisplayed">
                        {{ name }}
                        <a href="#" v-on:click="detach">&Cross;</a>
                    </div>
                    <div class="chat chat-room-content" v-bind:class="{ displayed: room.displayed }">

                        <ul class="chat chat-room-messages" ref="container">
                            <message v-for="message in room.messages" v-bind:message="message"></message>
                        </ul>
                        <form class="chat chat-form-message" v-on:submit.prevent="addMessage">
                            <label for="chat chat-message">
                                Message
                            </label>
                            <input type="text" v-model="message" class="chat chat-message-content" name="chat chat-message-content"/>
                            <input type="submit" name="chat chat-send-message" value="envoyer"/>
                        </form> 
                    </div>
                </li>
            </div>
            <div id="chat-message-template">
                <li class="chat chat-room-message" v-bind:class="{ 'is-self' : isSelf }">
                    <span>{{ message.content }}</span>
                </li>
            </div>
        </div>
        <div id="chat" style="display: none;">
            <div id="chat-search-friends" v-bind:focus="displaySearch">
                <form v-on:submit.prevent="search">
                    <input 
                        type="text" 
                        v-model="searched" 
                        v-on:focus="displaySearch" 
                        v-on:input="search" 
                        name="search-friend" 
                        id="search-friend" 
                        placeholder="<?php echo translate('Rechercher un ami'); ?>"/>
                    <div id="friend-suggestion" v-bind:class="{displayed: searchDisplayed}">
                        <a href="#" v-on:click="hideSearch">&Cross;</a>
                        <ul>
                            <li v-for="suggestion in friendSuggestions" class="chat chat-friend-suggestion">
                                <a v-on:click.prevent="select(suggestion)" href="#">{{ suggestion.login }} {{ suggestion.email }}</a>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>

            <ul id="chat-attached-rooms">
                <attached-room 
                    v-on:room-change-display="roomChangeDisplay" 
                    ref="roomComp" v-if="room.is_room_attached" 
                    v-for="room in rooms" 
                    v-bind:room="room"></attached-room>
            </ul>

        </div>

        <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/chat/cookie.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/chat/socket.io.js') ?>"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue"></script>
        <!--<script src="https://unpkg.com/vue-async-computed@3.3.0"></script>-->
        <script type="text/javascript">
            var chatSocket = io('<?php echo $_SERVER['HTTP_HOST'] ?>:18080' + '/chat');
            var apiUrl = '<?php echo base_url('chat/chat') ?>';
            var token = getCookie('resources_chat_token');
            var userId;
        </script>
        <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/chat/chat.js') ?>"></script>
    </body>
</html>
