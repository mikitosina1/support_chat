<?php

namespace Modules\SupportChat\App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\SupportChat\App\View\Components\SupportChat;
use Modules\SupportChat\Services\SupportChatService;

class SupportChatServiceProvider extends ServiceProvider
{
	protected string $moduleName = 'SupportChat';

	protected string $moduleNameLower = 'supportchat';

	/**
	 * Boot the application events.
	 * @throws BindingResolutionException
	 */
	public function boot(): void
	{
		$this->registerTranslations();
		$this->registerConfig();
		$this->registerViews();
		$this->loadMigrationsFrom(module_path($this->moduleName, 'Database/migrations'));
		$this->loadViewsFrom(__DIR__ . '/../../resources/views', $this->moduleNameLower);

		if ($this->app->make(SupportChatService::class)->isModuleActive()) {
			Blade::component($this->moduleNameLower, SupportChat::class);

			View::composer('supportchat::components.supportchat', function ($view) {
				$supportChat = new SupportChat();
				$view->with('supportChatAssets', $supportChat->getSupportChatAssets());
			});

			$this->app->booted(function () {
				View::share('supportChatAdminActions', [
					$this->moduleName => [
						'open_chat' => [
							'label' => 'Open Chat',
							'route' => route('admin.supportchat.index'),
							'icon'  => '💬',
						]
					]
				]);
			});
		}
	}

	/**
	 * Register the service provider.
	 */
	public function register(): void
	{
		$this->app->register(RouteServiceProvider::class);
		$this->app->singleton(SupportChatService::class, function ($app) {
			return new SupportChatService();
		});
	}

	/**
	 * Register translations.
	 */
	public function registerTranslations(): void
	{
		$langPath = resource_path('lang/' . $this->moduleNameLower);

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, $this->moduleNameLower);
			$this->loadJsonTranslationsFrom($langPath);
		} else {
			$this->loadTranslationsFrom(module_path($this->moduleName, 'resources/lang'), $this->moduleNameLower);
			$this->loadJsonTranslationsFrom(module_path($this->moduleName, 'resources/lang'));
		}
	}

	/**
	 * Register config.
	 */
	protected function registerConfig(): void
	{
		$this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php')], 'config');
		$this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
	}

	/**
	 * Register views.
	 */
	public function registerViews(): void
	{
		$viewPath = resource_path('views/modules' . $this->moduleNameLower);
		$sourcePath = module_path($this->moduleName, 'resources/views');

		$this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);

		$this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

		$componentNamespace = str_replace('/', '\\', config('modules.namespace') . '\\' . $this->moduleName . '\\' . config('modules.paths.generator.component-class.path'));
		Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
	}

	/**
	 * Get the services provided by the provider.
	 */
	public function provides(): array
	{
		return [];
	}

	private function getPublishableViewPaths(): array
	{
		$paths = [];
		foreach (config('view.paths') as $path) {
			if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
				$paths[] = $path . '/modules/' . $this->moduleNameLower;
			}
		}

		return $paths;
	}
}
