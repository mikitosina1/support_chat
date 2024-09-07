import axios from 'axios';
import $ from 'jquery';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = $;
console.log("AAAAAAAAAAAAA");
$(document).ready(function() {
	if (!$('#support-chat').length) {
		$.ajax({
			url: '/supportchat/index',
			method: 'GET',
			success: function(data) {
				$('body').append(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('Ошибка загрузки чата:', textStatus, errorThrown);
			}
		});
	}
});
