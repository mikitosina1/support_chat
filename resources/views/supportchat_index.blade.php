<x-app-layout>
	<div class="hidden user-data">
		@foreach($userAttr as $key => $attr)
			<div class="hidden" data-attribute={{ $key }} data-value={{$attr}}></div>
		@endforeach
	</div>
</x-app-layout>
