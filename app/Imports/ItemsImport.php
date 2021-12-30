<?php

namespace App\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class ItemsImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            foreach ($rows as $row)
            {
                $mark_code = $row[0];
                $name = $row[1];
                $item_type_id = '01';
                $internal_id = ($row[2])?:null;
                $model = ($row[3]) ? : null;
                $item_code = ($row[4])?:null;
                $unit_type_id = ($row[5])?:'NIU';
                $currency_type_id = ($row[6])?:'PEN';
                $cost_price = $row[7];
                $discount = $row[8];
                $mark_price = $row[9];
                $sale_affectation_igv_type_id = ($row[10])?:10;
                $affectation_igv_types_exonerated_unaffected = ['20','21','30','31','32','33','34','35','36','37'];
                if(in_array($sale_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {
                    $has_igv = true;
                }else{
                    $has_igv = (strtoupper($row[11]) === 'SI')?true:false;
                }
                $sale_unit_price = ($row[12])?:0;
                $purchase_unit_price = ($row[13])?:0;
                $purchase_affectation_igv_type_id = ($row[14])?:10;
                $stock = ($row[15])?:0;
                $stock_min = ($row[16])?:0;
                $category_name = $row[17];
                $brand_name = $row[18];
                $color_name = $row[19];
                $size_name = $row[20];
                $description = $row[21];
                $second_name = $row[22];
                $barcode = $row[23] ?? null;

                if($internal_id) {
                    $item = Item::where('internal_id', $internal_id)
                                ->first();
                } else {
                    $item = null;
                }

                if(!$item) {
                    $category = $category_name ? Category::updateOrCreate(['name' => $category_name]):null;
                    $brand = $brand_name ? Brand::updateOrCreate(['name' => $brand_name]):null;
                    $color = $color_name ? Color::updateOrCreate(['name' => $color_name]):null;
                    $size = $size_name ? Size::updateOrCreate(['name' => $size_name]):null;

                    Item::create([
                        'description' => $description,
                        'model' => $model,
                        'item_type_id' => $item_type_id,
                        'internal_id' => $internal_id,
                        'item_code' => $item_code,
                        'unit_type_id' => $unit_type_id,
                        'currency_type_id' => $currency_type_id,
                        'sale_unit_price' => $sale_unit_price,
                        'sale_affectation_igv_type_id' => $sale_affectation_igv_type_id,
                        'has_igv' => $has_igv,
                        'purchase_unit_price' => $purchase_unit_price,
                        'purchase_affectation_igv_type_id' => $purchase_affectation_igv_type_id,
                        'stock' => $stock,
                        'stock_min' => $stock_min,
                        'category_id' => optional($category)->id,
                        'brand_id' => optional($brand)->id,
                        'name' => $name,
                        'second_name' => $second_name,
                        'barcode' => $barcode,
                        ]);
                    $registered += 1;

                }else{
                    $item->update([
                        'description' => $description,
                        'model' => $model,
                        'item_type_id' => $item_type_id,
                        'internal_id' => $internal_id,
                        'item_code' => $item_code,
                        'unit_type_id' => $unit_type_id,
                        'currency_type_id' => $currency_type_id,
                        'sale_unit_price' => $sale_unit_price,
                        'sale_affectation_igv_type_id' => $sale_affectation_igv_type_id,
                        'has_igv' => $has_igv,
                        'purchase_unit_price' => $purchase_unit_price,
                        'purchase_affectation_igv_type_id' => $purchase_affectation_igv_type_id,
                        'stock_min' => $stock_min,
                        'name' => $name,
                        'second_name' => $second_name,
                        'barcode' => $barcode,
                    ]);
                    $registered += 1;
                }
            }
            $this->data = compact('total', 'registered');

    }

    public function getData()
    {
        return $this->data;
    }
}
