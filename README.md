# ecommerce

Extensive ecommerce site built with ability to add to cart without being logged in. Upgraded to Laravel 8.x 

## To run locally

Have [docker](https://docs.docker.com/engine/install/) & [docker-compose](https://docs.docker.com/compose/install/) installed on your operating system.


```bash
cp .env.example .env
make dev && make backend-migrate && make backend-seed
```

## Misc

The `Makefile` for this project contains useful commands for a Laravel application and can be found at [laravel-makefile](https://github.com/kkamara/laravel-makefile).

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[GNU](https://www.gnu.org/licenses/quick-guide-gplv3.html)
