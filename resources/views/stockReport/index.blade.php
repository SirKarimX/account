@extends('layouts.admin')
@section('page-title')
    {{__('Warehouse Stock')}}
@endsection

@section('action-button')
    <div class="row d-flex justify-content-end">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6 pt-lg-3 pt-xl-2">
            <div class="all-button-box">
                {{--<a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-url="{{ route('productservice.create') }}" data-ajax-popup="true" data-title="{{__('Create New Product')}}">
                <i class="fa fa-plus"></i> {{__('Create')}}
                </a>--}}
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr role="row">
                                <th>{{__('Name')}}</th>
                                <th>{{__('Sku')}}</th>
                                <th>{{__('Category')}}</th>
                                @foreach($warehouses as $key=>$value)
                                    <th class="text-center">{{ $value }}</th>
                                @endforeach
                                <th class="text-center">{{__('Total')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($productServices as $productService)
                                @php
                                    $total_item_stock = 0;
                                @endphp
                                <tr class="font-style">
                                    <td>{{ $productService->name}}</td>
                                    <td>{{ $productService->sku }}</td>
                                    <td>{{ !empty($productService->category)?$productService->category->name:'' }}</td>
                                    @foreach($warehouses as $id=>$name)
                                        @php
                                            $item_warehouse_stock = \App\StockTransaction::where('warehouse_id', $id)
                                                                        ->where('item_id', $productService->id)
                                                                        ->sum('quantity');
                                            $total_item_stock += $item_warehouse_stock;

                                        @endphp
                                        <td class="text-center">{{ $item_warehouse_stock }}</td>
                                    @endforeach
                                    <td class="text-center">{{ $total_item_stock }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection