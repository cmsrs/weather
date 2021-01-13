# Weather
Get temperature from API - OpenWeatherMap - Laravel project

* install dependency

```bash
composer install
```

* create vhost

* permission

```bash
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

* set database config in file .env and .env.testing

* add  OPEN_WEATHER_MAP_KEY to .env and .env.testing. This key you can obtain from: https://openweathermap.org/

* migrate db

```bash
php artisan migrate
```

* run tests

```bash
./vendor/bin/phpunit
```
Print screen

<img src="https://github.com/cmsrs/weather/blob/task1/zrzut.png" alt="Print screen" />
