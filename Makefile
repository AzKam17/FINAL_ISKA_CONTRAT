up:
	docker-compose up -d
stop:
	docker-compose stop
shell:
	docker-compose exec -u 1000 $(arg) bash
build:
	docker-compose build
watch:
	yarn run dev-server


fixtures:
	php bin/console d:f:l --no-interaction