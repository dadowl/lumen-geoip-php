<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoIP extends Model
{
    protected $table = "GeoIP";
    public $timestamps = false;

    public function checkExists(){
        $query = GeoIP::query()->where([
            ['vpn_name', '=', $this->vpn_name],
            ['ip', '=', $this->ip],
            ['local_ip', '=', $this->local_ip],
            ['latitude', '=', number_format($this->latitude, 2, '.', '')],
            ['longitude', '=', number_format($this->longitude, 2, '.', '')],
            ['is_deprecated', '!=', '1']
        ]);
        if ($query->exists()) {
            return true;
        } else {
            return false;
        }
    }
}
