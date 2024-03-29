![v4-ecommerce.png](https://raw.githubusercontent.com/kkamara/ecommerce/develop/public/v4-ecommerce.png)

# ecommerce-web

Extensive ecommerce site built with ability to add to cart without being logged in. Upgraded to Laravel 9.x 

## To run locally

Have [docker](https://docs.docker.com/engine/install/) & [docker-compose](https://docs.docker.com/compose/install/) installed on your operating system.

```bash
cp .env.example .env
make dev && make backend-migrate
```

## To run browser tests

```bash
vendor/bin/sail dusk
```

View browser test code: [/tree/develop/tests/Browser](https://github.com/kkamara/ecommerce/tree/develop/tests/Browser)

## Follow version 4

[Add reactjs with new theme (#67)](https://github.com/kkamara/ecommerce/issues/67) at https://cartcommerce.herokuapp.com/v4

## Api Documentation

View the api collection [here](https://documenter.getpostman.com/view/17125932/UVkvJYGS).

## Misc

The `Makefile` for this project contains useful commands for a Laravel application and can be found at [laravel-makefile](https://github.com/kkamara/laravel-makefile).

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[BSD](https://opensource.org/licenses/BSD-3-Clause)
