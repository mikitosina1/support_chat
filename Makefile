sc-migrate: ## Run migration
	php artisan migrate --path=Modules/SupportChat/Database/Migrations

ddev-sc-migrate: ## Run migration for ddev
	ddev exec php artisan migrate --path=Modules/SupportChat/Database/Migrations
