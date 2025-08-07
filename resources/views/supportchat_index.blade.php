@vite([
	'Modules/SupportChat/resources/assets/sass/support_chat.scss',
	'Modules/SupportChat/resources/assets/js/support_chat.js'
])

<x-app-layout>
	<div class="hidden user-data">
		@foreach($adminAttr as $key => $attr)
			<div class="hidden" data-attribute={{ $key }} data-value={{$attr}}></div>
		@endforeach
	</div>
	<div class="cloud">
		<div class="dark:bg-gray-800 shadow sm:rounded-lg mt-8 support-chat-block">
			@foreach($chatRooms as $attr)
				<a href="{{ route('admin.supportchat.room.show', ['room' => $attr['id']]) }}" class="chat-link">
					{{$attr['name']}}
					<span>
						created: {{$attr['created_at']}},
						updated: {{$attr['updated_at']}}
					</span>
				</a>
			@endforeach
		</div>
	</div>
</x-app-layout>
