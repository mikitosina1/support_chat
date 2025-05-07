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


	const closeBtn = $("#chat-header .open-btn");
	const supportChat = $("#support-chat");
	const supportChatBtn = $("#support-chat .visibility");
	const expandBtn = $("#support-chat .toggle-fullscreen");
	const iconUp = $(".visibility .icon-up");
	const iconClose = $(".visibility .icon-close");

	$.ajax({
		url: '/api/v1/chat/room',
		method: 'GET',
		success: function(response) {
			if (response.success && response.room) {
				supportChat.data('room-id', response.room.id);
				loadChatHistory(response.room.id);
			}
		},
		error: function(xhr) {
			console.error('Ошибка получения комнаты чата:', xhr.responseText);
		}
	});

	function loadChatHistory(roomId) {
		$.ajax({
			url: `/api/v1/chat/rooms/${roomId}/messages`,
			method: 'GET',
			success: function(response) {
				if (response.success && response.messages) {
					$('#chat-body').empty();

					response.messages.forEach(function(message) {
						const from = message.user_id ? 'user' : 'support';
						addMessage(message.message, from);
					});
				}
			}
		});
	}


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
		const csrfToken = $('meta[name="csrf-token"]').attr('content');
		if (message) {
			addMessage(message, 'user');
			input.val('');
			$.ajax({
				url: `/api/v1/chat/rooms/${roomId}/messages`,
				method: 'POST',
				data: {
					message: message,
					_token: csrfToken
				},
				headers: {
					'X-CSRF-TOKEN': csrfToken
				},
				success: function(response) {
					console.log('Message successfully sent.', response);
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

	// Handle support chat form submission
	$('form.supportchat-create').on('submit', function(e) {
		e.preventDefault();

		const form = $(this);
		const email = form.find('input[name="email"]').val();

		$.ajax({
			url: form.attr('action'),
			method: 'POST',
			data: {
				email: email,
				_token: $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response) {
				// Clear the form
				form.find('input[name="email"]').val('');

				// Show a success message
				addMessage(response.message, 'support');

				// Hide the email form and show the chat interface
				$('.create-support-chat').hide();
				$('#chat-input').show();
				$('#send-btn').show();
			},
			error: function(xhr) {
				// Handle validation errors
				if (xhr.responseJSON && xhr.responseJSON.errors) {
					const errors = xhr.responseJSON.errors;
					Object.keys(errors).forEach(key => {
						addMessage(errors[key][0], 'support');
					});
				} else {
					addMessage('Error creating chat room. Please try again.', 'support');
					console.error('Error:', xhr.responseText);
				}
			}
		});
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
