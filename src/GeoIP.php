<?php

namespace G4\GeoIP;

class GeoIP
{
    const COUNTRY_CODE_EU = 'EU';
    const ENCODING_ISO = 'ISO-8859-1';
    const ENCODING_UTF = 'UTF-8';
    const GEOIP2_DATABASE = '/usr/share/GeoIP/GeoIP2-City.mmdb';

    /**
     * @var string
     */
    private $ip;

    /**
     * @var array
     */
    private $record;

    /**
     * @param $ip
     */
    public function __construct($ip = null)
    {
        $this->ip = $ip;

        if ($this->isIpValid()) {
            $this->findRecord();
        }

        if ($this->isCountryCodeEu()) {
            $this->record = $this->getNonEuGeoIpData();
        }
    }

    /**
     * Area code (phone)
     * @return int
     */
    public function getAreaCode()
    {
        return $this->get('area_code');
    }

    /**
     * @return int
     */
    public function getDmaCode()
    {
        return $this->get('dma_code');
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->convert($this->get('city'));
    }

    /**
     * @return string
     */
    public function getContinentCode()
    {
        return $this->get('continent_code');
    }

    /**
     * Two letter country representation
     * @return string
     */
    public function getCountryCode()
    {
        return $this->get('country_code');
    }

    /**
     * Three letter country representation
     * @return string
     */
    public function getCountryCode3()
    {
        return $this->get('country_code3');
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->convert($this->get('country_name'));
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return double
     */
    public function getLatitude()
    {
        return $this->get('latitude');
    }

    /**
     * @return double
     */
    public function getLongitude()
    {
        return $this->get('longitude');
    }

    /**
     * @return int
     */
    public function getPostalCode()
    {
        return $this->get('postal_code');
    }

    /**
     * @return int
     */
    public function getRegion()
    {
        return $this->convert($this->get('region'));
    }

    /**
     * @return boolean
     */
    public function hasCity()
    {
        return $this->get('city') != null;
    }

    /**
     * @return boolean
     */
    public function hasCountryCode()
    {
        return $this->get('country_code') !== null;
    }

    /**
     * @return boolean
     */
    public function hasRecord()
    {
        return is_array($this->record) && !empty($this->record);
    }

    /**
     * @param string $ip
     * @return \G4\GeoIP\GeoIP
     */
    public function setIp($ip)
    {
        if ($this->ip !== $ip) {
            $this->ip = $ip;
            $this->findRecord();
        }
        return $this;
    }

    private function convert($value)
    {
        return mb_convert_encoding($value, self::ENCODING_UTF, self::ENCODING_ISO);
    }

    /**
     * @param string $key
     * @return bool
     */
    private function get($key)
    {
        return $this->hasRecord() ? $this->record[$key] : null;
    }

    private function findRecord()
    {
        $this->record = $this->isIpv6()
            ? $this->findRecordIpv6()
            : @geoip_record_by_name($this->ip);
    }

    private function findRecordIpv6()
    {
        if (!file_exists(self::GEOIP2_DATABASE)) {
            throw new \Exception("MaxMind GeoIP2 database not found at: " . self::GEOIP2_DATABASE);
        }

        $reader = new \GeoIp2\Database\Reader(self::GEOIP2_DATABASE);
        $result = $reader->city($this->ip);

        return [
            'continent_code' => $result->continent->code,
            'country_code'   => $result->country->isoCode,
            'country_code3'  => null,
            'country_name'   => $result->country->name,
            'region'         => isset($result->subdivisions[0]) ? $result->subdivisions[0]->isoCode : null,
            'city'           => $result->city->name,
            'postal_code'    => $result->postal->code,
            'latitude'       => $result->location->latitude,
            'longitude'      => $result->location->longitude,
            'dma_code'       => $result->location->metroCode,
            'area_code'      => null,
        ];
    }

    private function isIpValid()
    {
        return $this->ip !== null
        && filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }


    private function isIpv6()
    {
        return $this->ip !== null
        && filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * @return bool
     */
    private function isCountryCodeEu()
    {
        return $this->getCountryCode() === self::COUNTRY_CODE_EU;
    }

    /**
     * @return array
     */
    private function getNonEuGeoIpData()
    {
        return [
            'continent_code' => 'EU',
            'country_code'   => 'BE',
            'country_code3'  => 'BEL',
            'country_name'   => 'Belgium',
            'region'         => '11',
            'city'           => 'Brussels',
            'postal_code'    => '1080',
            'latitude'       => 50.843637,
            'longitude'      => 4.3497265,
            'dma_code'       => 0,
            'area_code'      => 0,
        ];
    }
}