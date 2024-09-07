<?php

namespace Modules\SupportChat\Services;

use Nwidart\Modules\Facades\Module;

class SupportChatService
{
	/**
	 * Check if the SupportChat module is active.
	 *
	 * @return bool
	 */
	public function isModuleActive(): bool
	{
		$enabledModules = Module::allEnabled();

		return isset($enabledModules['SupportChat']);
	}

	/**
	 * Get the path to a module's view.
	 *
	 * @param string $viewPath
	 * @return string|null
	 */
	public function getViewPath(string $viewPath): ?string
	{
		if ($this->isModuleActive()) {
			return view()->exists("supportchat::{$viewPath}") ? "supportchat::{$viewPath}" : null;
		}
		return null;
	}
}

