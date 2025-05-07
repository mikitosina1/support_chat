@foreach ($supportChatAssets as $asset)
	@vite($asset)
@endforeach
<div id="support-chat" class="dark:bg-gray-800 closed"  data-room-id="">
	<div id="chat-header" class="dark:text-gray-300">
		<div>@lang('supportchat::chat_lang.s_chat')</div>
		<div class="chat-controls">
			<button class="hidden toggle-fullscreen">⛶</button>
			<button class="visibility open-btn">
				<svg class="icon-up" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="18 15 12 9 6 15"></polyline>
				</svg>
				<svg class="icon-close hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>
	</div>
	<div id="chat-body">
		<div class="chat-message support-message">
			<div class="message-bubble">
				<div class="create-support-chat">
					@lang('supportchat::chat_lang.required_email')
{{--					<form class="supportchat-create" action="{{ route('supportchat.create') }}" method="POST">--}}
{{--						@csrf--}}
{{--						<input class="email" type="email" name="email" placeholder="example@mail.com">--}}
{{--						<button type="submit" class="text-gray-800 personalized">--}}
{{--							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">--}}
{{--								<path d="M19 3h-2v6H5.83L9.41 5.41 8 4l-6 6 6 6 1.41-1.41L5.83 11H19z"/>--}}
{{--							</svg>--}}
{{--						</button>--}}
{{--					</form>--}}
				</div>
			</div>
		</div>
	</div>
	<div id="chat-footer">
		<input type="text" id="chat-input" placeholder="{{ __('supportchat::chat_lang.placeholder') }}">
		<button id="send-btn" class="dark:text-gray-300">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
				 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<line x1="22" y1="2" x2="11" y2="13" />
				<polygon points="22 2 15 22 11 13 2 9 22 2" />
			</svg>
		</button>
	</div>
</div>
