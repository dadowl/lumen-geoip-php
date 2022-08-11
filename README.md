# Lumen GeoIP Service

## Requirements
- PHP >= 8

## Required packages:
- https://github.com/maxmind/GeoIP2-php

## Install
Install Composer packages:
> composer install

Copy .env file:
> cp .env.example .env

You must download the GeoIP database from [MaxMind Site](https://www.maxmind.com/) and place it in the following path:
> /storage/app/GeoLite2-City.mmdb

Also, you can specify the location of the file with the GEOIP database through the GEO_DATABASE_PATH parameter relative to the storage folder.

Specify the locale in which the response from the server will be returned.
Where:

> APP_LOCALE - standard return locale. Default: en;

> FALLBACK_LOCALE - the locale in which the response will be returned if something happened to APP_LOCALE. Default: en;

## Usage

You can run this using command:
> php -S localhost:8000 -t public

To get GeoIP data, you must make an HTTP request, specifying the IP to check in the GET parameter:
>  http://localhost:8000/?ip=<IP_Address>

You can also pass a specific locale for the response in the desired format via the locale parameter:
> http://localhost:8000/?ip=<IP_Address>&lang=en

You may not specify the ip parameter, in which case you will receive data for the address from which the request was sent.

After successful completion of the request, you will receive a JSON response with the following parameters:
> - country
> - subdivision
> - city
> - postal_code  
> - latitude
> - longitude
