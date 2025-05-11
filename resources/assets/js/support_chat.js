import axios from 'axios';
import $ from 'jquery';
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = $;
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


$(document).ready(function () {

	const token = $('meta[name="csrf-token"]').attr('content');
	const chatToken = $('meta[name="chat-token"]').attr('content');
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
				loadChatHistory(response.room.id, chatToken);
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
		if (message) {
			addMessage({
				message: message,
				user:  {role: 'user'},
				created_at: new Date().getTime()
			});
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
					if (response.room_id) {
						$("#support-chat").attr('data-room-id', response.room_id);
					}
				},
				error: function(xhr) {
					// console.error('Something went wrong:', xhr.responseText);
					addMessage({
						message:'Something went wrong. Try again.',
						user: {role: 'admin'},
						created_at: new Date().getTime()
					});
				}
			});
		}
	});

});

function addMessage(content) {
	const chatBody = $('#chat-body');
	const message = $('<div>', {
		class: `chat-message ${content.user.role}-message`,
	}).append(
		$('<div>', {
			class: 'message-bubble ',
		}).append(
			$('<div>', {
				class: 'message-sender',
				text: content.user.role
			}),
			$('<div>', {
				class: 'message-text',
				text: content.message
			}),
			$('<div>', {
				class: 'message-time',
				text: content.created_at
			})
		)

	);
	chatBody.append(message);
	chatBody.scrollTop(chatBody[0].scrollHeight); // auto rolling
}

function loadChatHistory(roomId, chatToken) {
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
			if (response.success && Array.isArray(response.messages)) {
				$('#chat-body').empty();

				response.messages.forEach(function(message) {
					addMessage(message);
				});
			} else {
				console.error('Invalid response format:', response);
				$('#chat-body').empty();

				addMessage({
					message: 'Error loading chat history',
					user: {role: 'admin'},
					created_at: new Date().getTime()
				});
			}
		},
		error: function(xhr) {
			console.error('Error loading chat history:', xhr.responseText);
			$('#chat-body').empty();
			addMessage({
				message: 'Error loading chat history',
				user: {role: 'admin'},
				created_at: new Date().getTime()
			});
		}
	});
}
