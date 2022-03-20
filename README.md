# Lumen GeoIP Service

## Requirements
- PHP >= 8

## Usage
Install Composer packages:
> composer install

You must download the GeoIP database from [MaxMind Site](https://www.maxmind.com/) and place it in the following path:
> /storage/app/GeoLite2-City.mmdb

You can run this using command:
> php -S localhost:8000 -t public

To get GeoIP data, you must make an HTTP request, specifying the IP to check in the GET parameter:
>  http://localhost:8000/?ip=<IP_Address>

After successful completion of the request, you will receive a JSON response with the following parameters:
> - country
> - subdivision
> - city
> - postal_code  
> - latitude
> - longitude
