TOOLS = docker-compose run --rm tools
CONSOLE = $(TOOLS) bin/console

.PHONY: clean

all: boot install db assets run

boot:
	docker-compose up -d

install:
	$(TOOLS) sh -c "composer install && yarn install"

db:
	$(CONSOLE) doctrine:database:create --if-not-exists
	- $(CONSOLE) doctrine:schema:create

assets:
	$(TOOLS) sh -c "npm run build-dev"

run:
	$(CONSOLE) server:run

watch:
	$(TOOLS) npm run watch

test:
	$(TOOLS) ./vendor/bin/simple-phpunit

clean:
	docker-compose down
