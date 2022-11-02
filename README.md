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
--form 'email="example@domain.co.uk"' \
--form 'password="Password1"' \
//will return a string for example "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE2NjczODg0OTksImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsIm5iZiI6MTY2NzM4ODQ5OSwiZXhwIjoxNjY3Mzg4ODU5LCJlbWFpbCI6InJpY2hhcmRAcGFybmFieS1raW5nLmNvLnVrIiwidXNlcl9pZCI6NH0.Nzmdn1KYn1iCUjYG_LSgV8AH3dYdzQ8FreI6tZ-Iom4"

//Fetch weather json using generated token
curl --location --request GET 'http://localhost/api/weather?location=chester' \
--header 'accept: application/json' \
--header 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE2NjczODg0OTksImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsIm5iZiI6MTY2NzM4ODQ5OSwiZXhwIjoxNjY3Mzg4ODU5LCJlbWFpbCI6InJpY2hhcmRAcGFybmFieS1raW5nLmNvLnVrIiwidXNlcl9pZCI6NH0.Nzmdn1KYn1iCUjYG_LSgV8AH3dYdzQ8FreI6tZ-Iom4'
//will return a json string on weather data

```

## Dependencies

This package requires the following dependencies:

* laravel/laravel

## License
[MIT](https://choosealicense.com/licenses/mit/)