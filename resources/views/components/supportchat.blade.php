@foreach ($supportChatAssets as $asset)
	@vite($asset)
@endforeach
<div id="support-chat" class="dark:bg-gray-800 closed">
	<div id="chat-header" class="dark:text-gray-300">@lang('supportchat::chat_lang.s_chat')
		<button class="visibility open-btn"></button>
	</div>
	<div id="chat-body">
		<!-- messages -->
	</div>
	<div id="chat-footer">
		<input type="text" id="chat-input" placeholder="{{ __('supportchat::chat_lang.placeholder') }}">
		<button id="send-btn" class="dark:text-gray-300">@lang('supportchat::chat_lang.send')</button>
	</div>
</div>
