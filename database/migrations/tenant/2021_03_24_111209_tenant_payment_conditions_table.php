<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPaymentConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_conditions', function (Blueprint $table) {
            $table->string('id')->index();
            $table->string('name');
            $table->integer('days')->default(0);
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_active')->default(true);
        });

        DB::table('payment_conditions')->insert([
            ['id' => '01', 'name' => 'Contado',  'days' => 0, 'is_active' => true, 'is_locked' => true],
            ['id' => '02', 'name' => 'Crédito',  'days' => 0, 'is_active' => true, 'is_locked' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_conditions');
    }
}
