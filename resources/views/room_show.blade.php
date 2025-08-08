@vite([
	'Modules/SupportChat/resources/assets/sass/support_chat.scss',
	'Modules/SupportChat/resources/assets/js/support_chat.js'
])

<x-app-layout>
	<div class="hidden user-data">
		@foreach($adminAttr as $key => $attr)
			<div class="hidden" data-attribute="{{ $key }}" data-value="{{ $attr }}"></div>
		@endforeach
	</div>

	<div class="dialog-container">
		<div class="dark:bg-gray-800 shadow sm:rounded-lg mt-8 p-6">
			<div class="dialog-header">
				<h2 class="text-xl font-semibold">{{ $room->name }}</h2>
				<a href="{{ route('admin.supportchat.index') }}" class="text-blue-500 hover:text-blue-700">
					← @lang('supportchat::chat_lang.back_to_prev_page')
				</a>
			</div>

			<div class="dialog-messages">
				@foreach($messages as $message)
					<div class="dialog-message {{ $message['user']['role'] }}-dialog-message">
						<div class="dialog-bubble">
							<div class="dialog-sender">
								{{ $message['user']['name'] }} {{ $message['user']['lastname'] }}
								<span class="text-xs">({{ $message['user']['role'] }})</span>
							</div>
							<div class="dialog-text">
								{{ $message['message'] }}
							</div>
							<div class="dialog-time">
								{{ $message['created_at'] }}
							</div>
						</div>
					</div>
				@endforeach
			</div>

			<div class="dialog-form">
				<form id="send-message-form" class="flex gap-2">
					@csrf
					<input type="text"
						   id="dialog-input"
						   class="flex-1 rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600"
						   placeholder="@lang('supportchat::chat_lang.placeholder')">
					<button type="button"
							id="dialog-send-btn"
							class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
						@lang('supportchat::chat_lang.send')
					</button>
				</form>
			</div>
		</div>
	</div>
</x-app-layout>
