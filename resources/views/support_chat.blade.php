<!-- resources/views/chat.blade.php -->
<div id="support-chat">
	<div id="chat-header">@lang('supportchat::chat_lang.s_chat')</div>
	<div id="chat-body">
		<!-- messages -->
	</div>
	<div id="chat-footer">
		<input type="text" id="chat-input" placeholder="{{ __('supportchat::chat_lang.placeholder') }}">
		<button id="send-btn">@lang('supportchat::chat_lang.send')</button>
	</div>
</div>
