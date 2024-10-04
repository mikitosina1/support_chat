## Support-chat by <span style="color:#008066;">@mikitosina1</span>
#### Support-chat for Laravel 10<br><hr>

* add to **resources/views/app.blade.php**  in body:
```
@if(Module::isEnabled('SupportChat'))
	<?php echo app()->view->make('supportchat::components.supportchat')->render(); ?>
@endif
```
* run `npm run vite build` to install styles
