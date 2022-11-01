# WeatherAPI

WeatherAPI is a Laravel based API that has endpoints for getting the weather from a weather api, with parameters to select a location.

## Installation

```bash
composer require richard-parnaby-king/weatherapi
```

## Usage

There are three API endpoints:

* POST /api/user/create - used to create a new user (optional if user has already been defined in Laravel)
* POST /api/user/token - provide user login credentials to generate a JWT token
* GET /api/weather - Requires the paramter "location" with the name of the city or postcode. Requires the Authorization header with the generated JWT token.

```php
//Create a User Token (assumes user has already been created).
curl --location --request POST 'http://localhost/api/user/token' \
--form 'email="richard@parnaby-king.co.uk"' \
--form 'password="Password1"' \
--form 'name="Richard PK"'
//will return a string for example "1|kvpnG0RnaaesVErrLSqyHXNBICRrUX5gM8RNt2YX"

//Fetch weather json using generated token
curl --location --request GET 'http://localhost/api/weather?location=chester' \
--header 'accept: application/json' \
--header 'Authorization: Bearer 1|kvpnG0RnaaesVErrLSqyHXNBICRrUX5gM8RNt2YX'
//will return a json string on weather data

```

## Dependencies

This package requires the following dependencies:

* laravel/laravel

## License
[MIT](https://choosealicense.com/licenses/mit/)