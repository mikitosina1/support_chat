sc-migrate: ## Run migration
	php artisan migrate --path=Modules/SupportChat/Database/migrations

ddev-sc-migrate: ## Run migration for ddev
	ddev exec php artisan migrate --path=Modules/SupportChat/Database/Migrations

ws-init: ## before running websocket server must to run utilitarian commands
	php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
	php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
	php artisan migrate
	echo " SupportChat module initialized without errors "

ws-start: ## websocket server start
	php artisan websockets:serve

ddev-ws-init: ## before running websocket server must to run utilitarian commands
	ddev exec php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
	ddev exec php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
	ddev exec php artisan migrate
	echo " SupportChat module initialized without errors "

ddev-ws-start: ## websocket server start for local
	ddev exec php artisan websockets:serve
