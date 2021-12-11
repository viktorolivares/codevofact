<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantItemMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_material', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('material_id');
            $table->decimal('qty', 12, 2)->default(0);
            $table->decimal('price', 12, 2)->default(0);
            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_material');
    }
}
