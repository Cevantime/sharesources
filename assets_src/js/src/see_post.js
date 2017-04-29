var $ = window.jQuery || require('jquery');

var postId = window.postId;

$.get(window.baseUrl+'myblog/notifySeePost/'+postId);


