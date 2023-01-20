@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de inventarios</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('reports.inventory.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">

                            <div class="row mb-4">

                                <div class="col-md-12">
                                    <form action="{{route('reports.inventory.index')}}" method="get">
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Almacén</span>
                                                    </div>
                                                    <select class="form-control" name="warehouse_id" id="">
                                                        <option {{ request()->warehouse_id == 'all' ?  'selected' : ''}} selected value="all">Todos</option>
                                                        @foreach($warehouses as $item)
                                                        <option {{ request()->warehouse_id == $item->id ?  'selected' : ''}} value="{{$item->id}}">
                                                            {{$item->description}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Marca</span>
                                                    </div>
                                                    <select class="form-control" name="brand_id" id="">
                                                        <option {{ request()->brand_id == 'all' ?  'selected' : ''}} selected value="all">Todos</option>
                                                        @foreach($brands as $item)
                                                        <option {{ request()->brand_id == $item->id ?  'selected' : ''}} value="{{$item->id}}">{{$item->name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-primary btn-block" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @if(isset($reports))
                                    <div class="col-md-6 mt-3">
                                        <form action="{{route('reports.inventory.pdf')}}" class="d-inline" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id ? request()->warehouse_id : 'all'}}">
                                            <button class="btn btn-custom" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        </form>

                                        <form action="{{route('reports.inventory.report_excel')}}" class="d-inline" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id ? request()->warehouse_id : 'all'}}">
                                            <button class="btn btn-custom" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                    <thead class="">
                                        <tr>
                                            <th>#</th>
                                            <th>Descripción</th>
                                            <th>Categoria</th>
                                            <th>Inventario actual</th>
                                            <th>Precio de venta</th>
                                            <th>Costo</th>
                                            <th>Marca</th>
                                            <th>Color</th>
                                            <th>Talla</th>
                                            <th>F. vencimiento</th>
                                            <th>Almacén</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $key => $value)
                                        <tr>
                                            <td class="celda">{{$loop->iteration}}</td>
                                            <td class="celda">{{$value->item->internal_id ?? ''}} {{$value->item->internal_id ? '-':''}} {{$value->item->description ?? ''}}</td>
                                            <td class="celda">{{optional($value->item->category)->name}}</td>
                                            <td class="celda">{{$value->stock}}</td>
                                            <td class="celda">{{$value->item->sale_unit_price}}</td>
                                            <td class="celda">{{$value->item->purchase_unit_price}}</td>
                                            <td class="celda">{{ isset($value->item->brand['name']) ? $value->item->brand['name'] : '' }}</td>
                                            <td class="celda">{{ isset($value->item->color['name']) ? $value->item->color['name'] : ''  }}</td>
                                            <td class="celda">{{ isset($value->item->size['name']) ? $value->item->size['name'] : '' }}</td>
                                            <td class="celda">{{ $value->item->date_of_due }}</td>
                                            <td class="celda">{{$value->warehouse->description}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            Total {{$reports->total()}}
                            <label class="pagination-wrapper ml-2">
                                {{$reports->appends($_GET)->render()}}
                            </label>
                        </div>
                    </div>
                    @else
                    <div class="box box-body no-padding">
                        <strong>No se encontraron registros</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
