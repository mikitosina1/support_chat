import axios from 'axios';
import $ from 'jquery';
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.$ = $;
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let translations = {};

// translations
function __(key, fallback) {
	if (translations[key]) {
		return translations[key];
	}
	return fallback || key;
}
йцуйцу
// getting translations
async function loadTranslations(chatToken) {
	try {
		const response = await $.ajax({
			url: '/api/v1/chat/translations',
			method: 'GET',
			headers: {
				'Authorization': `Bearer ${chatToken}`
			}
		});

		if (response && response.success && response.translations) {
			translations = response.translations;
		} else {
			console.warn('Can not see translations for chat:', response);
		}
	} catch (error) {
		console.error('Error translations catching:', error);
	}
}

async function getUserInfo() {
	try {
		return await $.ajax({
			url: '/get-user-info',
			method: 'GET'
		});
	} catch (error) {
		console.error(__('user_info_error', 'Error receiving user information: '), error);
		throw error;
	}
}

async function initializeRoom(chatToken) {
	if (!chatToken) {
		console.error(__('token_missing', 'Missing room initialisation token'));
		return Promise.reject(new Error(__('chat_token_missing', 'Missing chat token')));
	}

	try {
		const response = await $.ajax({
			url: '/api/v1/chat/room',
			method: 'GET',
			headers: {
				'Authorization': `Bearer ${chatToken}`
			}
		});

		if (response.success && response.room) {
			const roomId = response.room.id;
			$("#support-chat").attr('data-room-id', roomId);
			return roomId;
		} else {
			console.error(__('room_data_error', 'Failed to retrieve room data: '), response);
			return null;
		}
	} catch (error) {
		console.error(__('room_request_error', 'Failed to request room data: '), error);
		throw error;
	}
}

async function loadChatHistory(roomId, chatToken) {
	if (!roomId || !chatToken) {
		throw new Error(__('missing_params', 'Missing required parameters'));
	}

	try {
		const response = await $.ajax({
			url: `/api/v1/chat/rooms/${roomId}/messages`,
			method: 'GET',
			headers: {
				'Authorization': `Bearer ${chatToken}`
			}
		});

		if (response.success && Array.isArray(response.messages)) {
			$('#chat-body').empty();
			response.messages.forEach(function(message) {
				addMessage(message);
			});
			return response.messages;
		} else {
			console.error(__('invalid_response', 'Invalid response format: '), response);
			$('#chat-body').empty();
			addMessage({
				message: __('load_error', 'Error loading chat history'),
				user: {role: 'admin'},
				created_at: new Date().getTime()
			});
			return [];
		}

	} catch (error) {
		console.error(__('load_error', 'Error loading chat history:'), error);
		$('#chat-body').empty();
		addMessage({
			message: __('load_error', 'Error loading chat history'),
			user: {role: 'admin'},
			created_at: new Date().getTime()
		});
		throw error;
	}
}

async function sendMessage(roomId, message, chatToken, csrfToken) {
	try {
		return await $.ajax({
			url: `/api/v1/chat/rooms/${roomId}/messages`,
			method: 'POST',
			data: {
				message: message,
				_token: csrfToken
			},
			headers: {
				'X-CSRF-TOKEN': csrfToken,
				'Authorization': `Bearer ${chatToken}`
			}
		});
	} catch (error) {
		console.error(__('send_error_log', 'Error sending message: '), error);
		throw error;
	}
}

function addMessage(content) {
	const chatBody = $('#chat-body');

	// time formatting
	let formattedTime;
	if (typeof content.created_at === 'number' || !isNaN(new Date(content.created_at).getTime())) {
		const date = new Date(content.created_at);
		const locale = document.documentElement.lang || 'en';

		formattedTime = date.toLocaleTimeString(locale, {
			hour: '2-digit',
			minute: '2-digit'
		});
	} else {
		formattedTime = content.created_at;
	}

	const roleTranslation = __(content.user.role, content.user.role);
	const message = $('<div>', {
		class: `chat-message ${content.user.role}-message`,
	}).append(
		$('<div>', {
			class: 'message-bubble ',
		}).append(
			$('<div>', {
				class: 'message-sender',
				text: roleTranslation
			}),
			$('<div>', {
				class: 'message-text',
				text: content.message
			}),
			$('<div>', {
				class: 'message-time',
				text: formattedTime
			})
		)
	);
	chatBody.append(message);
	chatBody.scrollTop(chatBody[0].scrollHeight); // auto scroll
}

async function initializeChat() {
	try {

		const token = $('meta[name="csrf-token"]').attr('content');
		const chatToken = $('meta[name="chat-token"]').attr('content');
		const closeBtn = $("#chat-header .open-btn");
		const supportChat = $("#support-chat");
		const supportChatBtn = $("#support-chat .visibility");
		const expandBtn = $("#support-chat .toggle-fullscreen");
		const iconUp = $(".visibility .icon-up");
		const iconClose = $(".visibility .icon-close");

		if (chatToken === '' || chatToken === undefined) {
			$("#chat-footer").html(`<div class="auth-warning dark:text-gray-300">${__('auth_required', 'You must be logged in to send messages.')}</div>`);
			$('.chat-message.support-message').hide();
			return;
		}
		await loadTranslations(chatToken);

		const userInfo = await getUserInfo();
		console.log(userInfo);

		try {
			const roomId = await initializeRoom(chatToken);

			if (roomId) {
				await loadChatHistory(roomId, chatToken);
			} else {
				console.error(__('room_id_error', 'Failed to retrieve room ID'));
				addMessage({
					message: __('chat_init_error', 'Chat initialisation error. Try reloading the page.'),
					user: {role: 'admin'},
					created_at: new Date().getTime()
				});
			}
		} catch (error) {
			console.error(__('chat_init_log_error', 'Failed to initialise the chat room: '), error);
			addMessage({
				message: __('chat_init_error', 'Chat initialisation error. Try reloading the page.'),
				user: {role: 'admin'},
				created_at: new Date().getTime()
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

		$('#send-btn').on('click', async function () {
			const input = $('#chat-input');
			const message = input.val().trim();
			const roomId = supportChat.data('room-id') || 1;

			if (message) {
				addMessage({
					message: message,
					user: {role: 'user'},
					created_at: new Date().getTime()
				});

				input.val('');

				try {
					const response = await sendMessage(roomId, message, chatToken, token);
					if (response.room_id) {
						supportChat.attr('data-room-id', response.room_id);
					}
				} catch (error) {
					addMessage({
						message: __('send_error', 'Something went wrong. Try again.'),
						user: {role: 'admin'},
						created_at: new Date().getTime()
					});
				}
			}
		});

		console.log(__('chat_online', 'Support chat online'));
	} catch (error) {
		console.error(__('critical_error', 'Critical chat initialisation error: '), error);
	}
}

$(document).ready(function() {
	initializeChat()
		.catch(error => {
			console.error(__('chat_init_error', 'Chat initialisation error. Try reloading the page. '), error);
		});
});
