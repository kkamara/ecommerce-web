

sail := vendor/bin/sail

.PHONY: rm
rm:
	$(sail) down -v

.PHONY: docker-setup
docker-setup:
	$(sail) up -d # get services running
	sleep 120

.PHONY: backend-install
backend-install:
	$(sail) composer i

.PHONY: backend-setup
backend-setup:
	make backend-install
	$(sail) artisan key:generate

.PHONY: backend-migrate
backend-migrate:
	$(sail) artisan migrate --seed

.PHONY: frontend-clean
frontend-clean:
	rm -rf node_modules 2>/dev/null || true
	rm package-lock.json 2>/dev/null || true
	rm yarn.lock 2>/dev/null || true
	$(sail) yarn cache clean

.PHONY: frontend-install
frontend-install:
	make frontend-clean
	$(sail) yarn install
	$(sail) npx mix

.PHONY: dev
dev:
	make docker-setup
	make backend-setup
	make frontend-install

.PHONY: watch
watch:
	$(sail) npx mix watch
