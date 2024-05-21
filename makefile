laravel-serve:
	php artisan serve

vite-dev:
	npm run dev

dev:
	make -j2 laravel-serve vite-dev

# vim: noexpandtab
