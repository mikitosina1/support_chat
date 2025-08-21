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

	<div class="dialog-container"
		 data-room-id="{{ $room->id }}"
		 data-fetch-url="{{ route('admin.supportchat.room.messages.index', ['room' => $room->id]) }}"
		 data-post-url="{{ route('admin.supportchat.room.messages.store', ['room' => $room->id]) }}">
		<div class="dark:bg-gray-900 shadow sm:rounded-lg mt-8 p-6">
			<div class="dialog-header">
				<h2 class="text-xl font-semibold text-white">{{ $room->name }}</h2>
				<a href="{{ route('admin.supportchat.index') }}" class="text-white hover:text-blue-700">
					← @lang('supportchat::chat_lang.back_to_prev_page')
				</a>
			</div>

			<div class="dialog-messages" id="dialog-messages">
				@foreach($messages as $message)
					<div class="dialog-message {{ $message['user']['role'] }}-dialog-message"
						 data-id="{{ $message['id'] }}">
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
