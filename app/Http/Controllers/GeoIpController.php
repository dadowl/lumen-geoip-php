<?php

namespace App\Http\Controllers;

//require_once 'vendor/autoload.php';
use Exception;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeoIpController extends Controller
{

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $ip = $request->input("ip") ?? $request->server('REMOTE_ADDR');
        $locale = $request->input("locale") ?? env('APP_LOCALE', "en");

        $validator = Validator::make(
            compact('ip', 'locale'),
            [
                'ip' => 'ip',
                'locale' => 'min:2|max:3|in:de,en,es,fr,ja,ru',
            ]
        );

        if ($validator->fails()){
            $data = [];

            foreach ($validator->errors()->messages() as $key => $value){
                $data[$key] = $value[0];
            }

            $json = [
                "status" => "error",
                "message" => "Validation error.",
                "data" => $data,
                "request"=>[
                    'ip' => $ip,
                    'locale' => $locale
                ]
            ];

            return response()->json($json, 422);
        }

        try {
            $reader = new Reader(storage_path(env('GEO_DATABASE_PATH', "/app/GeoLite2-City.mmdb")));
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Database not found.",
                "request"=>[
                    'ip' => $ip,
                    'locale' => $locale
                ]
            ], 404);
        }

        try {
            $record = $reader->city($ip);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "IP not found.",
                "request"=>[
                    'ip' => $ip,
                    'locale' => $locale
                ]
            ], 404);
        }

        if (!array_key_exists($locale, $record->country->names)) {
            $locale = env('APP_LOCALE', "en");
        }

        if (!array_key_exists($locale, $record->country->names)) {
            $locale = env('FALLBACK_LOCALE', "en");
        }

        if (!array_key_exists($locale, $record->country->names)) {
            return response()->json([
                "status" => "error",
                "message" => "Locale not found.",
                "request"=>[
                    'ip' => $ip,
                    'locale' => $locale
                ]
            ], 404);
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
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

}
