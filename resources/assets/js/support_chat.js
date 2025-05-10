import axios from 'axios';
import $ from 'jquery';
// import Echo from 'laravel-echo';
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = $;
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


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
	//
	// const roomId = $("#support-chat").data('room-id');
	//
	// window.Echo.private(`chat.room.${roomId}`)
	// .listen('.new.message', (e) => {
	// 	// Добавляем сообщение в UI
	// 	const message = e.message;
	// 	const from = message.user_id === userId ? 'user' : 'support';
	// 	addMessage(message.message, from);
	// });

	const token = $('meta[name="csrf-token"]').attr('content');
	const chatToken = $('meta[name="chat-token"]').attr('content');
	console.log("AAAAA: ", token, "\n BBBB: ", chatToken);

	const closeBtn = $("#chat-header .open-btn");
	const supportChat = $("#support-chat");
	const supportChatBtn = $("#support-chat .visibility");
	const expandBtn = $("#support-chat .toggle-fullscreen");
	const iconUp = $(".visibility .icon-up");
	const iconClose = $(".visibility .icon-close");

	// Initialize room ID
	$.ajax({
		url: '/api/v1/chat/room',
		method: 'GET',
		headers: {
			'Authorization': `Bearer ${chatToken}`
		},
		success: function(response) {
			if (response.success && response.room) {
				$("#support-chat").attr('data-room-id', response.room.id);
				loadChatHistory(response.room.id, chatToken, token);
			}
		}
	});

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
		const roomId = $("#support-chat").data('room-id') || 1;
		console.log(token);
		if (message) {
			addMessage(message, 'user');
			input.val('');
			$.ajax({
				url: `/api/v1/chat/rooms/${roomId}/messages`,
				method: 'POST',
				data: {
					message: message,
					_token: token
				},
				headers: {
					'X-CSRF-TOKEN': token,
					'Authorization': `Bearer ${chatToken}`
				},
				success: function(response) {
					console.log('Message successfully sent.', response);
					if (response.room_id) {
						$("#support-chat").attr('data-room-id', response.room_id);
					}
				},
				error: function(xhr) {
					console.error('Something went wrong:', xhr.responseText);
					addMessage('Something went wrong. Try again.', 'support');
				}
			});


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

function loadChatHistory(roomId, chatToken, token) {
	if (!roomId || !chatToken) {
		console.error('Missing required parameters for loadChatHistory');
		return;
	}

	$.ajax({
		url: `/api/v1/chat/rooms/${roomId}/messages`,
		method: 'GET',
		headers: {
			'Authorization': `Bearer ${chatToken}`
		},
		success: function(response) {
			console.log('Chat history response:', response);
			
			if (response.success && Array.isArray(response.messages)) {
				$('#chat-body').empty();

				response.messages.forEach(function(message) {
					const from = message.user_id ? 'user' : 'support';
					addMessage(message.message, from);
				});
			} else {
				console.error('Invalid response format:', response);
				$('#chat-body').empty();
				addMessage('Error loading chat history', 'support');
			}
		},
		error: function(xhr) {
			console.error('Error loading chat history:', xhr.responseText);
			$('#chat-body').empty();
			addMessage('Error loading chat history', 'support');
		}
	});
}
