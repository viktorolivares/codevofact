<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantFixedAssetPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_asset_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->uuid('external_id');
            $table->unsignedInteger('establishment_id');
            $table->char('soap_type_id', 2);
            $table->char('state_type_id', 2);
            $table->char('group_id', 2);
            $table->string('document_type_id');
            $table->char('series', 4);
            $table->integer('number');
            $table->date('date_of_issue');
            $table->date('date_of_due')->nullable();
            $table->time('time_of_issue');
            $table->unsignedInteger('supplier_id');
            $table->text('supplier');
            $table->string('currency_type_id');
            $table->decimal('exchange_rate_sale', 13, 3);
            $table->decimal('total_prepayment', 12, 2)->default(0);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total_exportation', 12, 2)->default(0);
            $table->decimal('total_free', 12, 2)->default(0);
            $table->decimal('total_taxed', 12, 2)->default(0);
            $table->decimal('total_unaffected', 12, 2)->default(0);
            $table->decimal('total_exonerated', 12, 2)->default(0);
            $table->decimal('total_igv', 12, 2)->default(0);
            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);
            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->unsignedInteger('customer_id')->nullable();
            $table->date('perception_date')->nullable();
            $table->integer('perception_number')->nullable();
            $table->decimal('total_perception', 12, 2)->nullable();

            $table->text('charges')->nullable();
            $table->text('discounts')->nullable();
            $table->text('prepayments')->nullable();
            $table->text('guides')->nullable();
            $table->text('related')->nullable();
            $table->text('perception')->nullable();
            $table->text('detraction')->nullable();
            $table->text('legends')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('supplier_id')->references('id')->on('persons');
            $table->foreign('customer_id')->references('id')->on('persons');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
            $table->foreign('state_type_id')->references('id')->on('state_types');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('document_type_id')->references('id')->on('cat_document_types');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_asset_purchases');
    }
}
