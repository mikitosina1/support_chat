import axios from 'axios';
import $ from 'jquery';
import Echo from 'laravel-echo';
import Pusher from "pusher-js";
window.Pusher = Pusher;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = $;

$(document).ready(function () {

	// window.Echo = new Echo({
	// 	broadcaster: 'pusher',
	// 	key: 'local',
	// 	wsHost: 'ws.ddev.site',
	// 	cluster: 'mt1',
	// 	wsPort: 6001,
	// 	wssPort: 6001,
	// 	forceTLS: false,
	// 	encrypted: false,
	// 	disableStats: true,
	// });

	const closeBtn = $("#chat-header .open-btn");
	const supportChat = $("#support-chat");
	const supportChatBtn = $("#support-chat .visibility");
	const expandBtn = $("#support-chat .toggle-fullscreen");
	const iconUp = $(".visibility .icon-up");
	const iconClose = $(".visibility .icon-close");

	closeBtn.on('click', function () {
		if (supportChat.hasClass('closed')) {
			supportChat.removeClass('closed');
			supportChatBtn.removeClass('open-btn');
			expandBtn.removeClass('hidden');
			iconUp.addClass('hidden');
			iconClose.removeClass('hidden');
		} else {
			supportChat.addClass('closed');
			supportChat.removeClass('fullscreen');
			supportChatBtn.addClass('open-btn');
			expandBtn.addClass('hidden');
			expandBtn.text('⛶');
			iconUp.removeClass('hidden');
			iconClose.addClass('hidden');
		}
	});

	expandBtn.on('click', function () {
		supportChat.toggleClass('fullscreen');

		if (supportChat.hasClass('fullscreen')) {
			expandBtn.text('🗕');
		} else {
			expandBtn.text('⛶');
		}
	});

	console.log("Support chat online");

	$('#send-btn').on('click', function () {
		const input = $('#chat-input');
		const message = input.val().trim();
		if (message) {
			addMessage(message, 'user');
			input.val('');

			setTimeout(() => {
				addMessage('Thanks for answer, we will answer so fast as we can!', 'support');
			}, 1000);
		}
	});

});

function addMessage(content, from = 'user') {
	const chatBody = $('#chat-body');
	const message = $('<div>', {
		class: `chat-message ${from}-message`,
	}).append(
		$('<div>', {
			class: 'message-bubble',
			text: content,
		})
	);
	chatBody.append(message);
	chatBody.scrollTop(chatBody[0].scrollHeight); // auto rolling
}
