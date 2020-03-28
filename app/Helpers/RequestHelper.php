<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use OK\Ipstack\Client as Ipstack;
use Stevebauman\Location\Facades\Location;
use Vectorface\Whip\Whip;

class RequestHelper
{
    /**
     * Get client ip.
     *
     * @return array|string
     */
    public static function ip()
    {
        $whip = new Whip();
        $ip = $whip->getValidIpAddress();
        if ($ip === false) {
            $ip = Request::header('Cf-Connecting-Ip');
            if (is_null($ip)) {
                $ip = Request::ip();
            }
        }

        return $ip;
    }

    /**
     * Get client country.
     *
     * @param string $ip
     * @return string|null
     */
    public static function country($ip)
    {
        $position = Location::get($ip);

        if ($position) {
            return $position->countryCode;
        }
    }

    /**
     * Get client country and currency.
     *
     * @param string|null $ip
     * @return array
     */
    public static function infos($ip)
    {
        $ip = $ip ?? static::ip();

        if (config('location.ipstack_apikey') != null) {
            $ipstack = new Ipstack(config('location.ipstack_apikey'));
            $position = $ipstack->get($ip, true);

            if (! is_null($position) && Arr::get($position, 'country_code', null)) {
                return [
                    'country' => Arr::get($position, 'country_code', null),
                    'currency' => Arr::get($position, 'currency.code', null),
                    'timezone' => Arr::get($position, 'time_zone.id', null),
                ];
            }
        }

        return [
            'country' => static::country($ip),
            'currency' => null,
            'timezone' => null,
        ];
    }
}
