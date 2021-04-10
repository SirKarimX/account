@extends('layouts.admin')
@section('page-title')
    {{__('Stock Adjustment')}}
@endsection

@section('action-button')
    <div class="row d-flex justify-content-end">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
            <div class="all-button-box">
                <a href="{{ route('stock-adjustment.create',0) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fas fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0 mt-2">
                    {{--<div class="row d-flex justify-content-end">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                            <div class="all-select-box">
                                <div class="btn-box">
                                    {{ Form::label('issue_date', __('Date'),['class'=>'text-type']) }}
                                    {{ Form::text('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:null, array('class' => 'form-control month-btn datepicker-range')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                            <div class="all-select-box">
                                <div class="btn-box">
                                    {{ Form::label('status', __('Status'),['class'=>'text-type']) }}
                                    {{ Form::select('status', [''=>'All']+$status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <a href="#" class="apply-btn"
                               onclick="document.getElementById('customer_submit').submit(); return false;"
                               data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                            </a>
                            <a href="{{route('customer.index')}}" class="reset-btn" data-toggle="tooltip"
                               data-original-title="{{__('Reset')}}">
                                <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
                            </a>
                        </div>
                    </div>--}}
                    {{ Form::close() }}
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Adjustment ID')}}</th>
                                <th> {{__('Adjustment Date')}}</th>
                                <th>{{__('Warehouse')}}</th>
                                <th>{{__('Product')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($adjustments as $adjustment)
                                <tr>
                                    <td>
                                        <a href="{{ route('stock-adjustment.show',\Crypt::encrypt($adjustment->id)) }}">{{ Auth::user()->adjustmentlNumberFormat($adjustment->id) }}</a>
                                    </td>
                                    <td class="Id">{{ Auth::user()->dateFormat($adjustment->dated) }}</td>
                                    <td>{{ $adjustment->warehouse }}</td>
                                    <td>{{ $adjustment->product }}</td>
                                    <td>{{ $adjustment->quantity }}</td>

                                    <td class="Action">
                                            <span>
                                                <a href="{{ route('stock-adjustment.edit',Crypt::encrypt($adjustment->id)) }}"
                                                       class="edit-icon" data-toggle="tooltip"
                                                       data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                    <a href="#" class="delete-icon " data-toggle="tooltip"
                                                       data-original-title="{{__('Delete')}}"
                                                       data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}"
                                                       data-confirm-yes="document.getElementById('delete-form-{{$adjustment->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['stock-adjustment.destroy', $adjustment->id],'id'=>'delete-form-'.$adjustment->id]) !!}
                                                {!! Form::close() !!}
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
