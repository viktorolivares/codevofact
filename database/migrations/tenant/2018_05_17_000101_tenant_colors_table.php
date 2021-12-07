<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('colors')->insert([
            ['name' => 'Biege'],
            ['name' => 'Blanco'],
            ['name' => 'Negro'],
            ['name' => 'Blanco y Negro'],
            ['name' => 'Camel'],
            ['name' => 'Hueso'],
            ['name' => 'Hueso y Marrón'],
            ['name' => 'Lila'],
            ['name' => 'Marrón'],
            ['name' => 'Perla'],
            ['name' => 'Vintage'],
            ['name' => 'Verde Militar'],
            ['name' => 'Perla y Marrón'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors');
    }
}
