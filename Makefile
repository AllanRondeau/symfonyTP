COMPOSE=docker compose -f compose.yaml -f compose.override.yaml
BASH=$(COMPOSE) exec app
CONSOLE=$(BASH) php bin/console

.PHONY: start up perm db cc bash vendor stop

start: up perm vendor db cc perm

up:
	docker kill $$(docker ps -q) || true
	$(COMPOSE) build --force-rm
	$(COMPOSE) up -d --remove-orphans

stop:
	$(COMPOSE) stop
	$(COMPOSE) kill

vendor:
	$(BASH) composer install -n
	make perm

bash:
	$(BASH) bash

db:
	$(CONSOLE) doctrine:database:drop --if-exists --force
	$(CONSOLE) doctrine:database:create --if-not-exists
	$(CONSOLE) doctrine:migrations:migrate -n
	$(CONSOLE) doctrine:fixtures:load -n

perm:
	sudo chown -R $(USER):$(USER) .
	mkdir -p ./var ./public/uploads/ ./public/uploads/company/
	sudo chown -R www-data:$(USER) ./var ./public/uploads/ ./public/uploads/company/
	sudo chmod -R g+rwx .

cc:
	$(CONSOLE) c:cl --no-warmup
	$(CONSOLE) c:warmup

php-linter:
	sh -c "COMPOSE_INTERACTIVE_NO_CLI=1 $(BASH) vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php"

