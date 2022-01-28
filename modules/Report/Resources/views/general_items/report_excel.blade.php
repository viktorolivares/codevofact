<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>REPORTE PRODUCTOS</title>
    </head>
    <body>
        @if(!empty($records))
            <div>
                <div class=" ">
                    <table>
                        <thead>
                            <tr>
                                <th>FECHA DE EMISIÓN</th>
                                <th>MARCA</th>
                                <th>CÓDIGO MARCA</th>
                                <th>CÓDIGO INTERNO</th>
                                <th>TALLA</th>
                                <th>DESCRIPCIÓN</th>
                                <th>NOMBRE</th>
                                <th>COLOR</th>
                                <th>PRECIO CONCEPT</th>
                                <th>PRECIO MARCA</th>
                                <th>DESCUENTO MARCA</th>
                                <th>DESCUENTO PRODUCTO</th>
                                <th>PRECIO COSTO</th>
                                <th>CANTIDAD</th>
                                <th>METODO PAGO</th>
                                <th>VALOR UNITARIO</th>
                                <th>PRECIO VENTA</th>
                                <th>SUBTOTAL</th>
                                <th>IGV</th>
                                <th>TOTAL</th>
                                <th>ANULADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($type == 'sale')

                                @if($document_type_id == '80')

                                    @foreach($records as $key => $value)

                                        @php
                                            $series = '';
                                            if(isset($value->item->lots) )
                                            {
                                                $series_data =  collect($value->item->lots)->where('has_sale', 1)->pluck('series')->toArray();
                                                $series = implode(" - ", $series_data);
                                            }

                                            $total_item_purchase = \Modules\Report\Http\Resources\GeneralItemCollection::getPurchaseUnitPrice($value);
                                            $utility_item = $value->total - $total_item_purchase;

                                        @endphp
                                        <tr>
                                            <td class="celda">{{$value->sale_note->date_of_issue->format('Y-m-d')}}</td>
                                            <td class="celda">NOTA DE VENTA</td>
                                            <td class="celda">80</td>
                                            <td class="celda">{{$value->sale_note->series}}</td>
                                            <td class="celda">{{$value->sale_note->number}}</td>
                                            <td class="celda">{{$value->sale_note->state_type_id == '11' ? 'SI':'NO'}}</td>
                                            <td class="celda">{{$value->sale_note->customer->identity_document_type->description}}</td>
                                            <td class="celda">{{$value->sale_note->customer->number}}</td>
                                            <td class="celda">{{$value->sale_note->customer->name}}</td>
                                            <td class="celda">{{$value->sale_note->currency_type_id}}</td>
                                            <td class="celda">{{$value->sale_note->exchange_rate_sale}}</td>
                                            <td class="celda">{{$value->sale_note->unit_type_id}}</td>
                                            <td class="celda">{{$value->relation_item->brand->name}}</td>
                                            <td class="celda">{{$value->item->description}}</td>
                                            <td class="celda">{{$value->quantity}}</td>
                                            <td class="celda">{{$series}}</td>
                                            <td class="celda">{{($value->relation_item) ? $value->relation_item->purchase_unit_price:0}}</td>
                                            <td class="celda">{{$value->unit_value}}</td>
                                            <td class="celda">{{$value->unit_price}}</td>
                                            <td class="celda">{{$value->total_discount}}</td>
                                            <td class="celda">{{$value->total_value}}</td>
                                            <td class="celda">{{$value->total_igv}}</td>
                                            <td class="celda">{{$value->total_plastic_bag_taxes}}</td>
                                            <td class="celda">{{$value->total}}</td>
                                            <td class="celda">{{ number_format($total_item_purchase,2) }}</td>
                                            <td class="celda">{{ number_format($utility_item ,2) }}</td>
                                            <td class="celda">{{$value->relation_item->brand->name}}</td>
                                        </tr>
                                    @endforeach

                                @else

                                    @foreach($records as $key => $value)

                                        @php
                                            $series = '';
                                            if(isset($value->item->lots) )
                                            {
                                                $series_data =  collect($value->item->lots)->where('has_sale', 1)->pluck('series')->toArray();
                                                $series = implode(" - ", $series_data);
                                            }

                                            $total_item_purchase = \Modules\Report\Http\Resources\GeneralItemCollection::getPurchaseUnitPrice($value);
                                            $utility_item = $value->total - $total_item_purchase;
                                        @endphp

                                    <tr>
                                        <td class="celda">{{$value->document->date_of_issue->format('Y-m-d')}}</td>
                                        <td class="celda">{{$value->relation_item->brand->name}}</td>
                                        <td class="celda">{{$value->relation_item->mark_code}}</td>
                                        <td class="celda">{{$value->item->internal_id}}</td>
                                        <td class="celda">{{$value->relation_item->size->name}}</td>
                                        <td class="celda">{{$value->item->description}}</td>
                                        <td class="celda">{{$value->relation_item->description}}</td>
                                        <td class="celda">{{$value->relation_item->color->name}}</td>
                                        <td class="celda">{{$value->relation_item->price_concept}}</td>
                                        <td class="celda">{{$value->relation_item->mark_price}}</td>
                                        <td class="celda">{{$value->relation_item->discount_mark}}</td>
                                        <td class="celda">{{$value->relation_item->discount_product}}</td>
                                        <td class="celda">{{$value->relation_item->cost_price}}</td>
                                        <td class="celda">{{$value->quantity}}</td>
                                        <td class="celda">{{$value->document->payment_method_type}}</td>
                                        <td class="celda">{{($value->relation_item) ? $value->relation_item->purchase_unit_price:0}}</td>
                                        <td class="celda">{{$value->unit_price}}</td>
                                        <td class="celda">{{$value->total_value}}</td>
                                        <td class="celda">{{$value->total_igv}}</td>
                                        <td class="celda">{{$value->total}}</td>
                                        <td class="celda">{{$value->document->state_type_id == '11' ? 'SI':'NO'}}</td>
                                    </tr>
                                    @endforeach
                                @endif


                            @else

                                @foreach($records as $key => $value)
                                <tr>
                                    <td class="celda">{{$value->purchase->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{$value->purchase->document_type->description}}</td>
                                    <td class="celda">{{$value->purchase->document_type_id}}</td>
                                    <td class="celda">{{$value->purchase->series}}</td>
                                    <td class="celda">{{$value->purchase->number}}</td>
                                    <td class="celda">{{$value->purchase->state_type_id == '11' ? 'SI':'NO'}}</td>
                                    <td class="celda">{{$value->purchase->supplier->identity_document_type->description}}</td>
                                    <td class="celda">{{$value->purchase->supplier->number}}</td>
                                    <td class="celda">{{$value->purchase->supplier->name}}</td>
                                    <td class="celda">{{$value->purchase->currency_type_id}}</td>
                                    <td class="celda">{{$value->purchase->exchange_rate_sale}}</td>
                                    <td class="celda">{{$value->item->unit_type_id}}</td>
                                    <td class="celda">{{$value->relation_item ? $value->relation_item->internal_id:''}}</td>
                                    <td class="celda">{{$$value->relation_item->mark_code}}</td>
                                    <td class="celda">{{$value->item->description}}</td>
                                    <td class="celda">{{$$value->relation_item->name}}</td>
                                    <td class="celda">{{$value->quantity}}</td>
                                    <td class="celda"></td>
                                    <td class="celda"></td>
                                    <td class="celda">{{$value->unit_value}}</td>
                                    <td class="celda">{{$value->unit_price}}</td>
                                    <td class="celda">
                                    @if($value->discounts)
                                        {{collect($value->discounts)->sum('amount')}}
                                    @endif
                                    </td>

                                    <td class="celda">{{$value->total_value}}</td>
                                    <td class="celda">{{$value->affectation_igv_type_id}}</td>
                                    <td class="celda">{{$value->total_igv}}</td>
                                    <td class="celda">{{$value->system_isc_type_id}}</td>
                                    <td class="celda">{{$value->total_isc}}</td>
                                    <td class="celda">{{$value->total_plastic_bag_taxes}}</td>

                                    <td class="celda">{{$value->total}}</td>
                                    <td class="celda"></td>

                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
