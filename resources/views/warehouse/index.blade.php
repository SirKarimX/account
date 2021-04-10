@extends('layouts.admin')
@section('page-title')
    {{__('Manage Warehouses')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create warehouse')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('warehouse.create') }}" data-ajax-popup="true" data-title="{{__('Create New Warehouse')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fas fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Warehouse Name')}}</th>
                                <th> {{__('Description')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($warehouses as $warehouse)
                                <tr>
                                    <td class="font-style">{{ $warehouse->name }}</td>
                                    <td class="font-style">{{ $warehouse->description }}</td>
                                    <td class="Action">
                                        <span>
                                        @can('edit warehouse')
                                                <a href="#" class="edit-icon" data-url="{{ route('warehouse.edit',$warehouse->id) }}" data-ajax-popup="true" data-title="{{__('Edit Warehouse')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            @endcan
                                            @can('delete warehouse')
                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$warehouse->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['warehouse.destroy', $warehouse->id],'id'=>'delete-form-'.$warehouse->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </span>
                                    </td>
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
