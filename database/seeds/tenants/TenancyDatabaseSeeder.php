<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TenancyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('format_templates')->insert([
            ['id'=> 1, 'formats' => 'con_valor_unitario'],
            ['id'=> 2, 'formats' => 'default'],
            ['id'=> 3, 'formats' => 'default2'],
            ['id'=> 4, 'formats' => 'font_sm'],
            ['id'=> 5, 'formats' => 'font_sw2'],
            ['id'=> 6, 'formats' => 'legend_amazonia'],
            ['id'=> 7, 'formats' => 'model1'],
            ['id'=> 8, 'formats' => 'model2'],
            ['id'=> 9, 'formats' => 'model3'],
            ['id'=> 10, 'formats' => 'model4'],
            ['id'=> 11, 'formats' => 'modelw80'],
            ['id'=> 12, 'formats' => 'santiago'],
            ['id'=> 13, 'formats' => 'top_placa'],
            ['id'=> 14, 'formats' => 'unit_types_desc']
        ]);
    }
}
