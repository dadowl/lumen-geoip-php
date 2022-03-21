<?php

namespace App\Http\Controllers;

//require_once 'vendor/autoload.php';
use Exception;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class GeoIpController extends Controller
{

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        $ip = app('request')->get("ip");

        if ($ip == null){
            return response()->json([
                "request" => [
                    "status" => "error",
                    "message" => "IP not transmitted.",
                    "data" => []
                ]
            ]);
        }

        try {
            $reader = new Reader(storage_path('/app/GeoLite2-City.mmdb'));
        } catch (Exception $e) {
            return response()->json([
                "request" => [
                    "status" => "error",
                    "message" => "Database not found.",
                    "data" => []
                ]
            ]);
        }

        try {
            $record = $reader->city($ip);
        } catch (Exception $e) {
            return response()->json([
                "request" => [
                    "status" => "error",
                    "message" => "IP not found.",
                    "data" => []
                ]
            ]);
        }

        return response()->json([
            "request" => [
                "status" => "successful",
                "data" => [
                    "country" => $record->country->name,
                    "subdivision" =>$record->mostSpecificSubdivision->name,
                    "city" => $record->city->name,
                    "postal_code" => $record->postal->code,
                    "latitude" => $record->location->latitude,
                    "longitude" => $record->location->longitude
                ]
            ]
        ]);
    }

}
