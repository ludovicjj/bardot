.PHONY: serve worker build watch cc install help

help:
	@echo "Available targets:"
	@echo "  serve    - Start FrankenPHP on http://localhost:8000"
	@echo "  worker   - Start the Messenger worker"
	@echo "  build    - Build frontend assets (production)"
	@echo "  watch    - Watch frontend assets (dev)"
	@echo "  cc       - Clear Symfony cache"
	@echo "  install  - Install composer + npm dependencies"

serve:
	frankenphp php-server --listen :8000 --root public/

worker:
	frankenphp php-cli bin/console messenger:consume async -vv

build:
	npm run build

watch:
	npm run watch

cc:
	frankenphp php-cli bin/console cache:clear

install:
	composer install
	npm ci
