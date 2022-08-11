<?php

namespace App\Http\Controllers;

//require_once 'vendor/autoload.php';
use Exception;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Http\Request;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class GeoIpController extends Controller
{

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $ip = app('request')->get("ip");
        $envLocale = env('GEO_LOCALE', "en");
        $locale = $envLocale;


        if ($ip == null) {
            $ip = $request->server('REMOTE_ADDR');
        }

        if (app('request')->get("locale") != null){
            $locale = app('request')->get("locale");
        }

        try {
            $reader = new Reader(storage_path('/app/GeoLite2-City.mmdb'));
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Database not found.",
                "data" => [],
                "request"=>[
                    'ip' => $ip,
                    'locale' => $locale
                ]
            ]);
        }

        try {
            $record = $reader->city($ip);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "IP not found.",
                "data" => [],
                "request"=>[
                    'ip' => $ip,
                    'locale' => $locale
                ]
            ]);
        }

        if (!array_key_exists($locale, $record->country->names)) {
            $locale = $envLocale;
        }

        return response()->json([
            "status" => "successful",
            "message" => "",
            "data" => [
                "country" => $record->country->names[$locale],
                "subdivision" => $record->mostSpecificSubdivision->names[$locale],
                "city" => $record->city->names[$locale],
                "postal_code" => $record->postal->code,
                "latitude" => $record->location->latitude,
                "longitude" => $record->location->longitude
            ],
            "request"=>[
                'ip' => $ip,
                'locale' => $locale
            ]
        ]);
    }

}
