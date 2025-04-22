@foreach ($supportChatAssets as $asset)
	@vite($asset)
@endforeach
<div id="support-chat" class="dark:bg-gray-800 closed">
	<div id="chat-header" class="dark:text-gray-300">
		<div>@lang('supportchat::chat_lang.s_chat')</div>
		<div class="chat-controls">
			<button class="hidden toggle-fullscreen">⛶</button>
			<button class="visibility open-btn"></button>
		</div>
	</div>
	<div id="chat-body">
		<div class="chat-message user-message">
			<div class="message-bubble">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
		</div>
		<div class="chat-message support-message">
			<div class="message-bubble">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
		</div>
	</div>
	<div id="chat-footer">
		<input type="text" id="chat-input" placeholder="{{ __('supportchat::chat_lang.placeholder') }}">
		<button id="send-btn" class="dark:text-gray-300">@lang('supportchat::chat_lang.send')</button>
	</div>
</div>
