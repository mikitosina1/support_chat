<?php

namespace Modules\SupportChat\App\View\Components;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Nwidart\Modules\Module;

class SupportChat extends Component
{
	public function render(): Factory|\Illuminate\Foundation\Application|View|Htmlable|Closure|string|Application
	{
		return view('supportchat::components.supportchat', $this->getSupportChatAssets());
	}

	public function getSupportChatAssets(): array
	{
		$assets = Module::getAssets();

		return array_filter($assets, function ($asset) {
			return str_contains($asset, 'Modules/SupportChat');
		});
	}
}
