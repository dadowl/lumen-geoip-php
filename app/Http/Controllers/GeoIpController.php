<?php

namespace App\Http\Controllers;

//require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class GeoIpController extends Controller
{

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $ip = app('request')->get("ip");
        if ($ip == null){
            throw new BadRequestException("IP not transmitted.");
        }

        $reader = new Reader(storage_path('/app/GeoLite2-City.mmdb'));
        try {
            $record = $reader->city($ip);
        } catch (AddressNotFoundException $e) {
            throw new BadRequestException("IP not found.");
        }

        $data = [
            "country" => $record->country->name,
            "subdivision" =>$record->mostSpecificSubdivision->name,
            "city" => $record->city->name,
            "postal_code" => $record->postal->code,
            "latitude" => $record->location->latitude,
            "longitude" => $record->location->longitude
        ];
        
        return response(json_encode($data))->withHeaders([
            'Content-Type' => "application/json",
        ]);
    }

}
