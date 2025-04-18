import axios from 'axios';
import $ from 'jquery';
import Echo from 'laravel-echo';
import Pusher from "pusher-js";
window.Pusher = Pusher;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = $;

$(document).ready(function () {

	window.Echo = new Echo({
		broadcaster: 'pusher',
		key: 'local',
		wsHost: 'ws.ddev.site',
		cluster: 'mt1',
		wsPort: 6001,
		wssPort: 6001,
		forceTLS: false,
		encrypted: false,
		disableStats: true,
	});

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

	console.log("Support chat online");
});
