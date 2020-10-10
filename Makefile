enable-xdebug:
	docker-compose exec -u 0 php docker-php-ext-enable xdebug
	docker-compose stop php && docker-compose start php
