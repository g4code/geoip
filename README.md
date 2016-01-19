geoip
======

> geoip - [php](http://php.net) library

## Install
Via Composer

```sh
composer require g4/geoip
```
## Requirements

* PHP GeoIP extension http://php.net/manual/en/geoip.setup.php
* Maxmind GeoIP database http://dev.maxmind.com/

## Usage

``` php
<?php
    
$geoIp = new \G4\GeoIP\GeoIP('127.0.0.1');

// "area_code" -- The PSTN area code (ex: 212)
echo $geoIp->getAreaCode();

// "dma_code" -- Designated Market Area code (USA and Canada only)
echo $geoIp->getDmaCode();

// "city" -- The city
echo $geoIp->getCity();

// "continent_code" -- Two letter continent code
echo $geoIp->getContinentCode();

// "country_code" -- Two letter country code
echo $geoIp->getCountryCode();

// "country_code3" -- Three letter country code
echo $geoIp->getCountryCode3();

// "country_name" -- The country name
echo $geoIp->getCountryName();

// IP address
echo $geoIp->getIp();

// "latitude" -- The Latitude as signed double
echo $geoIp->getLatitude();

// "longitude" -- The Longitude as signed double
echo $geoIp->getLongitude();

// "postal_code" -- The Postal Code, FSA or Zip Code
echo $geoIp->getPostalCode();

// "region" -- The region code (ex: CA for California)
echo $geoIp->getRegion();

```

[geoip_record_by_name](http://php.net/manual/en/function.geoip-record-by-name.php)

## Development

### Install dependencies

    $ make install

### Run tests

    $ make test

## License

(The MIT License)
see LICENSE file for details...
