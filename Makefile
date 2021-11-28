rm:
	./vendor/bin/sail down -v

docker-setup:
	./vendor/bin/sail up -d # get services running
	sleep 120

backend-install:
	./vendor/bin/sail composer i

backend-setup:
	make backend-install
	./vendor/bin/sail artisan key:generate

backend-migrate:
	./vendor/bin/sail artisan migrate --seed

frontend-clean:
	@rm -rf node_modules 2>/dev/null || true
	@rm package-lock.json 2>/dev/null || true
	./vendor/bin/sail npm cache clean --force

frontend-install:
	make frontend-clean
	./vendor/bin/sail npm i
	./vendor/bin/sail npx mix

dev:
	make docker-setup
	make backend-setup
	make frontend-install

watch:
	./vendor/bin/sail npx mix watch
