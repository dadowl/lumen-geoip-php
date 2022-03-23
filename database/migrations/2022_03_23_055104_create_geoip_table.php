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
            $table->string("common_name");
            $table->string("real_address");
            $table->string("status_path");
            $table->string("virtual_address");

            $table->float("lat");
            $table->float("long");

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
