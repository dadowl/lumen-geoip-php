<?php

namespace App\Http\Controllers;

//require_once 'vendor/autoload.php';
use App\Models\GeoIP;
use Exception;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DataController extends Controller
{

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        $vpn_name = app('request')->get("vpn_name");
        $ip = app('request')->get("ip");
        $local_ip = app('request')->get("local_ip");

        if ($vpn_name == null || $ip == null || $local_ip == null){
            return response()->json([
                "status" => "error",
                "message" => "Required parameters not passed.",
            ]);
        }

        $model = new GeoIP();

        $model->vpn_name = $vpn_name;
        $model->ip = $ip;
        $model->local_ip = $local_ip;

        try {
            $reader = new Reader(storage_path('/app/GeoLite2-City.mmdb'));
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Database not found.",
            ]);
        }

        try {
            $record = $reader->city($ip);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "IP not found.",
            ]);
        }

        $model->latitude = $record->location->latitude;
        $model->longitude = $record->location->longitude;

        try {
            $model->save();
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Internal Server Error ".$e,
            ]);
        }

        return response()->json([
            "status" => "successful",
            "message" => "Saved",
        ]);
    }

}

