<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GeoIP', function (Blueprint $table) {
            $table->id();
            $table->string("vpn_name");
            $table->string("ip");
            $table->string("local_ip");

            $table->float("latitude");
            $table->float("longitude");

            $table->boolean("is_deprecated")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geoip');
    }
};
