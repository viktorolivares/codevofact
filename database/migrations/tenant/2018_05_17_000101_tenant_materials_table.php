<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
        });

        DB::table('materials')->insert([
            ['name' => 'Tela', 'description' => 'Corduroy'],
            ['name' => 'Tela', 'description' => 'Cuerina'],
            ['name' => 'Tela', 'description' => 'Jean'],
            ['name' => 'Tela', 'description' => 'Lana Buclé'],
            ['name' => 'Tela', 'description' => 'Lino Crudo'],
            ['name' => 'Tela', 'description' => 'Lino Shantu'],
            ['name' => 'Tela', 'description' => 'Lino Natural'],
            ['name' => 'Tela', 'description' => 'Corduroy'],
            ['name' => 'Tela', 'description' => 'Loma'],
            ['name' => 'Tela', 'description' => 'Rib Duro'],
            ['name' => 'Tela', 'description' => 'Seda'],
            ['name' => 'Tela', 'description' => 'Suplex'],
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
