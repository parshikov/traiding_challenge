## Run

Fill `.env` file with your credentials of DB and Alpha Vantage API

```dotenv
DB_DATABASE=forge
DB_USERNAME=forge
DB_PASSWORD=secret

CACHE_DRIVER=file

API_KEY=<your Alpha Vantage API key>
```

Run docker containers

```bash
docker-compose up -d
```

Api is available on `80` port. Database is available on `5432` port.

Install dependencies and run migrations

```bash
docker-compose exec php composer install # yes, with dev dependencies if you want to run tests
docker-compose exec php ./artisan migrate --seed
```

## Run tests

Run tests (optionally with coverage)

```bash
docker-compose exec php composer install
docker-compose exec php ./vendor/bin/phpunit --coverage-html ./coverage
```

## Check API

Api is available on `http://localhost/`

Correct answer available on:

- http://localhost/stock/USD:GBP
- http://localhost/trend/USD:GBP

Not enough data answer available on:

- http://localhost/stock/USD:CHF

Unknown pair answer available on:

- http://localhost/stock/randomstring
- http://localhost/trend/randomstring

## CRON

I did not install cron to run background job to get data from external API.

If you want to run cron task manually, you can do it with:

via laravel scheduler

```bash
docker-compose exec php ./artisan schedule:run
```

or via direct command

```bash
docker-compose exec php ./artisan app:receive-data-command
```

## What to change/improve

It is not a real project, it is just a test task.

I implemented logic without using Dependency Injection and hidden configuration of everything inside of `config` dir
and `AppServiceProvider`.
It was done to simplify the task and to make it possible to run it without any additional configuration.

In big real project I would:

- move configuration to the `config` dir and hide all the logic behind services from Dependency Injection
- split getting data from external API into several chunks to prevent throttling
- something else (there is always something to improve)
- some notes are in the code

