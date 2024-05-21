laravel-serve:
	php artisan serve

vite-dev:
	npm run dev

setup-dev:
	npm install
	composer install
	cp .env.example .env
	php artisan key:generate

dev:
	make -j2 laravel-serve vite-dev

# vim: noexpandtab
