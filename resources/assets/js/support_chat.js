import axios from 'axios';
import $ from 'jquery';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = $;

$(document).ready(function() {
	console.log("Support chat online");

	let closeBtn = $("#chat-header .close-btn");
	let supportChat = $("#support-chat");

	closeBtn.on('click', function() {
		if (supportChat.hasClass('closed')) {
			supportChat.removeClass('closed');
		} else {
			supportChat.addClass('closed');
		}
	});
});
