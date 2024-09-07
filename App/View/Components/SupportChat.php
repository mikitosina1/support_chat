<?php

namespace Modules\SupportChat\App\View\Components;

use Illuminate\View\Component;
use Nwidart\Modules\Module;

class SupportChat extends Component
{
	public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|\Closure|string|\Illuminate\Contracts\Foundation\Application
	{
		$assets = Module::getAssets();

		$supportChatAssets = array_filter($assets, function ($asset) {
			return str_contains($asset, 'Modules/SupportChat');
		});

		return view('supportchat::components.supportchat', compact('supportChatAssets'));
	}
}
