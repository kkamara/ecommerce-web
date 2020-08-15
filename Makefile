docker-setup:
	docker-compose build # let's build our services
	docker-compose up -d # get services running

backend-setup:
	@docker exec e-app composer i
	@docker exec e-app php artisan key:generate
	@docker exec e-app php artisan migrate

make backend-seed:
	@docker exec e-app php artisan db:seed

clean-js-dep:
	@docker exec e-app bash -c "\
		rm -rf node_modules;\
		rm package-lock.json;\
		npm cache clean --force"

install-js-dep:
	make clean-js-dep
	@docker exec e-app npm i
	@docker exec e-app npm run dev

dev:
	make docker-setup
	sleep 20
	make backend-setup
	make backend-seed
	make clean-js-dep
	make install-js-dep