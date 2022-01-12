<?php

namespace App\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;
use Modules\Item\Models\Color;
use Modules\Item\Models\Size;
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

            $code = Item::max('id');
            $code = $code + 1;
            $code = str_pad($code,6,"0",STR_PAD_LEFT);
            $code = 'IAL'.$code;

            foreach ($rows as $row)
            {
                $internal_id = ($row[0])?:$code;
                $mark_code = $row[1];
                $brand_name = $row[2];
                $name = $row[3];
                $color_name = $row[4];
                $size_name = $row[5];
                $purchase_unit_price = ($row[6])?:0;
                $cost_price = ($row[7])?:0;
                $mark_price = $row[8]?:0;
                $discount_mark = $row[9]?:0;

                $sale_affectation_igv_type_id = ($row[20])?:10;
                $purchase_affectation_igv_type_id = ($row[21])?:10;

                $affectation_igv_types_exonerated_unaffected = ['20','21','30','31','32','33','34','35','36','37'];
                if(in_array($sale_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {
                    $has_igv = true;
                }else{
                    $has_igv = (strtoupper($row[10]) === 'SI')?true:false;
                }

                $price_concept = ($row[11])?:0;
                $discount_product = $row[12]?:0;
                $sale_unit_price = ($row[13])?:0;
                $stock = ($row[14])?:0;
                $stock_min = ($row[15])?:0;
                $description = $row[16];
                $second_name = $row[17];
                $unit_type_id = ($row[18])?:'NIU';
                $currency_type_id = ($row[19])?:'PEN';
                $barcode = $row[22] ?? null;

                $item_type_id = '01';
                $item_code = null;
                $category_name = "Mujer";
                $model = null;

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
                        'mark_code' => $mark_code,
                        'mark_price' => $mark_price,
                        'discount_product' => $discount_product,
                        'discount_mark' => $discount_mark,
                        'price_concept' => $price_concept,
                        'cost_price' => $cost_price,
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
                        'color_id' => optional($color)->id,
                        'size_id' => optional($size)->id,
                        'name' => $name,
                        'second_name' => $second_name,
                        'barcode' => $barcode,
                        ]);
                    $registered += 1;

                }else{
                    $item->update([
                        'description' => $description,
                        'model' => $model,
                        'mark_code' => $mark_code,
                        'mark_price' => $mark_price,
                        'discount_product' => $discount_product,
                        'discount_mark' => $discount_mark,
                        'price_concept' => $price_concept,
                        'cost_price' => $cost_price,
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
                        'mark_code' => $mark_code,
                        'mark_price' => $mark_price,
                        'discount_product' => $discount_product,
                        'discount_mark' => $discount_mark,
                        'cost_price' => $cost_price,
                        'price_concept' => $price_concept,
                    ]);
                    $registered += 1;
                }
            }
            $this->data = compact('total', 'registered');

    }

    public function getData()
    {
        return $this->data;
        //Update
    }
}
