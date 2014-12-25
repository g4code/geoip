<?php

namespace G4\GeoIP;

class GeoIP
{
    const ENCODING_ISO = 'ISO-8859-1';
    const ENCODING_UTF = 'UTF-8';

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
        $this->record = @geoip_record_by_name($this->ip);
    }

    private function isIpValid()
    {
        return $this->ip !== null
            && filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}