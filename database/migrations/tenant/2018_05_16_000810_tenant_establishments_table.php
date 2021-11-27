<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->char('country_id', 2);
            $table->char('department_id', 2);
            $table->char('province_id', 4);
            $table->char('district_id', 6);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('code')->nullable();
            $table->string('number')->nullable();
            $table->string('logo', 150)->nullable();
            $table->string('identity_document_type_id')->default(6);
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('identity_document_type_id')->references('id')->on('cat_identity_document_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establishments');
    }
}
