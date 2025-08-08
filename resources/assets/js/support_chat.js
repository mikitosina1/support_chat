import axios from 'axios';
import $ from 'jquery';

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

		closeBtn.click(function () {
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

/**
 * ------------------------------
 * Admin page of dialog (web)
 * ------------------------------
 * Initialized only with active .dialog-container
 */
(function initAdminDialog() {
	const container = document.querySelector('.dialog-container');
	if (!container) return; // if no container, do nothing

	const messagesWrap = document.getElementById('dialog-messages');
	const input = document.getElementById('dialog-input');
	const sendBtn = document.getElementById('dialog-send-btn');
	const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
	const fetchUrl = container.getAttribute('data-fetch-url');
	const postUrl = container.getAttribute('data-post-url');

	function getLastMessageId() {
		const nodes = messagesWrap.querySelectorAll('.dialog-message[data-id]');
		if (!nodes.length) return 0;
		const last = nodes[nodes.length - 1].getAttribute('data-id');
		return parseInt(last || '0', 10) || 0;
	}

	function escapeHtml(str) {
		const div = document.createElement('div');
		div.innerText = str ?? '';
		return div.innerHTML;
	}

	function renderMessage(message) {
		const wrapper = document.createElement('div');
		const role = message?.user?.role || 'user';
		wrapper.className = `dialog-message ${role}-dialog-message`;
		if (message.id) wrapper.setAttribute('data-id', message.id);

		const name = [message?.user?.name, message?.user?.lastname].filter(Boolean).join(' ');
		wrapper.innerHTML = `
			<div class="dialog-bubble">
				<div class="dialog-sender">
					${escapeHtml(name)}
					<span class="text-xs">(${escapeHtml(role)})</span>
				</div>
				<div class="dialog-text">${escapeHtml(message.message)}</div>
				<div class="dialog-time">${escapeHtml(message.created_at || '')}</div>
			</div>
		`;
		messagesWrap.appendChild(wrapper);
		messagesWrap.scrollTop = messagesWrap.scrollHeight;
	}

	async function pollNew() {
		try {
			const afterId = getLastMessageId();
			const url = afterId ? `${fetchUrl}?after_id=${afterId}` : fetchUrl;
			const res = await fetch(url, {
				headers: { 'X-Requested-With': 'XMLHttpRequest' }
			});
			const data = await res.json();
			if (data?.success && Array.isArray(data.messages)) {
				data.messages.forEach(renderMessage);
			}
		} catch (e) {
			console.error('Polling error:', e);
		}
	}

	async function send() {
		const message = (input.value || '').trim();
		if (!message) return;

		// show the message in the chat in a moment (optional)
		renderMessage({
			message,
			created_at: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
			user: { role: 'admin', name: '', lastname: '' }
		});

		input.value = '';

		try {
			await fetch(postUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': csrf,
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: JSON.stringify({ message })
			});
			// after sending poll new messages
			await pollNew();
		} catch (e) {
			console.error('Send error:', e);
		}
	}

	sendBtn?.addEventListener('click', send);
	input?.addEventListener('keydown', (e) => {
		if (e.key === 'Enter') {
			e.preventDefault();
			send();
		}
	});

	// start polling new messages
	pollNew();
	setInterval(pollNew, 12000);
})();
