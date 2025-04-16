## Support-chat by <span style="color:#008066;">@mikitosina1</span>

#### Laravel Support-chat for Laravel 10<br><hr>

* Ensure, that you have `nwidart/laravel-modules` and `beyondcode/laravel-websockets` or install them before setting up
	module.
* run `make ws-init` and `make ws-start` or if you haven't 'make' package:
* * move to file Modules/SupportChat/Makefile
* * run manually subcommands (look upper)
* add to **resources/views/app.blade.php**  in body:

```php
@if(Module::isEnabled('SupportChat'))
	<?php echo app()->view->make('supportchat::components.supportchat')->render(); ?>
@endif
```

* run `npm run vite build` to install styles
* change/create file in root of project 'vite.config.js'

```js
const allPaths = await collectModuleAssetsPaths(paths, 'Modules');

return defineConfig({
	plugins: [
		laravel({
			input: allPaths,
			refresh: true,
		})
	]
});
``` 

## Full root vite.config.js example:
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import collectModuleAssetsPaths from './vite-module-loader.js';

async function getConfig() {
	const paths = [
		// css
		'resources/css/app.css',
        //...

		// js
		'resources/js/app.js',
        //...
	];
	const allPaths = await collectModuleAssetsPaths(paths, 'Modules');

	return defineConfig({
		plugins: [
			laravel({
				input: allPaths,
				refresh: true,
			})
		]
	});
}

export default getConfig();

```
