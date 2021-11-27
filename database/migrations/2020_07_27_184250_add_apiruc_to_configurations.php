<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApirucToConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->text('url_apiruc')->nullable();
            $table->text('token_apiruc')->nullable();
        });

        DB::table('configurations')->update([
            'id' => '1',
            'url_apiruc' => 'https://apiperu.dev',
            'token_apiruc' => '0fdd98613a3ad09314afba254c8bc745020cc4d917a180839ad1e1bb5862f7fe'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->dropColumn(['url_apiruc', 'token_apiruc']);
        });
    }
}
