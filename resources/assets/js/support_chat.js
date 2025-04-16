import axios from 'axios';
import $ from 'jquery';
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
	broadcaster: 'pusher',
	key: 'AAaAglMZVbyyy7u206',
	wsHost: window.location.hostname,
	wsPort: 6001,
	forceTLS: false,
	disableStats: true,
});


window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = $;

$(document).ready(function () {
	console.log("Support chat online");

	let closeBtn = $("#chat-header");
	let supportChat = $("#support-chat");
	let supportChatBtn = $("#support-chat .visibility");

	closeBtn.on('click', function () {
		if (supportChat.hasClass('closed')) {
			supportChat.removeClass('closed');
			supportChatBtn.removeClass('open-btn');
			supportChatBtn.addClass('close-btn');
		} else {
			supportChat.addClass('closed');
			supportChatBtn.addClass('open-btn');
			supportChatBtn.removeClass('close-btn');
		}
	});
});
