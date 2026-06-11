<?php

namespace Modules\SupportChat\Services;

use Nwidart\Modules\Facades\Module;

class SupportChatService
{
    /**
     * Get the path to a module's view.
     */
    public function getViewPath(string $viewPath): ?string
    {
        if ($this->isModuleActive()) {
            return view()->exists("supportchat::{$viewPath}") ? "supportchat::{$viewPath}" : null;
        }

        return null;
    }

    /**
     * Check if the SupportChat module is active.
     */
    public function isModuleActive(): bool
    {
        $enabledModules = Module::allEnabled();

        return isset($enabledModules['SupportChat']);
    }
}
