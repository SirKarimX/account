@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Manage Employee-Detail')}}
@endsection
@section('action-button')
    <div class="row d-flex justify-content-end">
        @can('create payment')
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="all-button-box">
                 <a href="#" data-size="2xl" data-url="{{ route('employee.create') }}" data-ajax-popup="true" data-title="{{__('Create New Payment')}}" class="btn btn-xs btn-white btn-icon-only width-auto commonModal">
                    {{__('Create Payment')}}
                </a>
                </div>
            </div>
        @endcan
        @can('manage user')
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="all-button-box">
                 <a href="#" data-size="2xl" data-url="{{ route( 'employee.create-attendance') }}" data-ajax-popup="true" data-title="{{__('Create New Attendance')}}" class="btn btn-xs btn-white btn-icon-only width-auto commonModal">
                    {{__('Create Attendance')}}
                </a>
                </div>
            </div>
        @endcan
           @can('edit user')
            <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="#" data-size="2xl" data-url="{{ route('employee.edit',$user['id']) }}" data-ajax-popup="true" data-title="{{__('Edit User')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                </div>
            </div>
        @endcan
        @can('delete user')
            <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="#" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $user['id']}}').submit();" class="btn btn-xs btn-white bg-danger btn-icon-only width-auto">
                        <i class="fa fa-trash"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        @endcan
    </div>
@endsection





@section('content')
    
 
    <div class="row">
        <div class="col-md-12">
            <div class="card pb-0">
                <h3 class="small-title">{{__('Employee Info')}}</h3>
                <div class="row">
                 
                    <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('User Id')}}</h5>
                            <h5 class="report-text mb-3"> {{ $user['id'] }}</h5>
                            <h5 class="report-text gray-text mb-0">{{__('Email')}}</h5>
                            <h5 class="report-text mb-3"> {{ $user['email'] }}</h5>

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('Name')}}</h5>
                            <h5 class="report-text mb-3"> {{ $user['name'] }}</h5>
                            <h5 class="report-text gray-text mb-0">{{__('Role')}}</h5>
                            <h5 class="report-text mb-3"> {{ $user['type'] }}</h5>
                             <h5 class="report-text gray-text mb-0">{{__('Month')}}</h5>
                            <h5 class="report-text mb-3"> {{now()->format('F Y')}}</h5>
                        </div>
                    </div>
                     <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('Salary')}}</h5>
                            <h5 class="report-text mb-3"> {{ $user['salary'] }}</h5>
                            <h5 class="report-text gray-text mb-0">{{__('Target')}}</h5>
                            <h5 class="report-text mb-3"> {{ $user['target'] }}</h5>
                             <h5 class="report-text gray-text mb-0">{{__('Commision')}}</h5>
                            <h5 class="report-text mb-3">{{ $user['commission'] }}%</h5>
                                     <h5 class="report-text gray-text mb-0">{{__('No not Attend')}}</h5>
                            <h5 class="report-text mb-3"> 0 Day</h5>
                        </div>
                    </div>
@php

 $totalInvoicePaymentSum=$user->userTotalPaymentInvoice($user['id']);
                      
                          $TotalPaymentSum=$user->userTotalPaymentSum($user['id']);
                           $balancePayment=$user['salary'] - $TotalPaymentSum;
                        $TotalCommission=$totalInvoicePaymentSum*$user['commission']/100;
                    @endphp

                    <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('Received')}}</h5>
                            <h5 class="report-text mb-3">{{\Auth::user()->priceFormat($TotalPaymentSum)}}</h5>
                            <h5 class="report-text gray-text mb-0">{{__('Achived')}}</h5>
                            <h5 class="report-text mb-3"> {{\Auth::user()->priceFormat($totalInvoicePaymentSum)}}</h5></h5>
                            <h5 class="report-text gray-text mb-0">{{__('Commission Amount')}}</h5>
                            <h5 class="report-text mb-3">  @if($Usercommission=$totalInvoicePaymentSum>=$user['target'])
  
                            {{\Auth::user()->priceFormat($TotalCommission)}}
    
                              @else

                                0

                            @endif
                        </h5>
                            <h5 class="report-text gray-text mb-0">{{__('Balance')}}</h5>
                            <h5 class="report-text mb-0">

@if($totalInvoicePaymentSum>=$user['target'])
                                {{\Auth::user()->priceFormat($balancePayment+$TotalCommission)}}
@else
 {{\Auth::user()->priceFormat($balancePayment)}}
 @endif
                            </h5>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Payment')}}</h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                 <th>{{__('Amount')}}</th>
                                    <th>{{__('Type')}}</th>
                                <th>{{__('Desc')}}</th>
                               
                                
                               
                                    <th> {{__('Action')}}</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                       @foreach    ($user->userPayment($user->id) as $payment)
                                <tr>
                                <th> {{ \Auth::user()->dateFormat($payment->date) }}</th>
                                <th>{{ $payment->amount }}</th>
                               <th>{{ $payment->type }}</th>
                                <th>{{ $payment->description }}</th>
                             
                                
                               
                              @if(Gate::check('edit payment') || Gate::check('delete payment'))
                                        <th class="action text-right">
                                            @can('edit payment')
                                                <a href="#" class="edit-icon" data-url="{{ route('payment.edit',$payment->id) }}" data-ajax-popup="true" data-title="{{__('Edit Payment')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                            @can('delete payment')
                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['payment.destroy', $payment->id],'id'=>'delete-form-'.$payment->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </th>
                                    @endif


                                </tr>
                         @endforeach
                              </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
        
  
    <div class="row">
        <div class="col-12">

            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Attendance')}}</h5>

            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                
                                <th>{{__('Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                
                               
                                    <th> {{__('Action')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            
                                <tr>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Invoice')}}</h5>

            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Invoice')}}</th>
                                <th>{{__('Customer')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Due Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($user->userInvoice($user->id) as $invoice)
                                <tr>
                                          <td class="Id">
                                        @if(\Auth::guard('customer')->check())
                                            <a href="{{ route('customer.invoice.show',\Crypt::encrypt($invoice->id)) }}">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}
                                            </a>
                                        @else
                                            <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{  (!empty($invoice->customer)?$invoice->customer->name:'-')}}</td>
                                    <td>
                                        @if(($invoice->issue_date < date('Y-m-d')))
                                            <p class="text-danger"> {{ \Auth::user()->dateFormat($invoice->issue_date) }}</p>
                                        @else
                                            {{ \Auth::user()->dateFormat($invoice->issue_date) }}
                                        @endif
                                    </td>
                                    <td>{{\Auth::user()->priceFormat($invoice->getDue())  }}</td>
                                    <td>
                                        @if($invoice->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <td class="Action">
                                            <span>
                                            @can('duplicate invoice')
                                                    <a href="#" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Duplicate')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$invoice->id}}').submit();">
                                                    <i class="fas fa-copy"></i>
                                                    {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id],'id'=>'duplicate-form-'.$invoice->id]) !!}
                                                        {!! Form::close() !!}
                                                </a>
                                                @endcan
                                                @can('show invoice')
                                                    @if(\Auth::guard('customer')->check())
                                                        <a href="{{ route('customer.invoice.show',\Crypt::encrypt($invoice->id)) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @else
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endif
                                                @endcan
                                                @can('edit invoice')
                                                    <a href="{{ route('invoice.edit',\Crypt::encrypt($invoice->id)) }}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete invoice')
                                                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$invoice->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]) !!}
                                                    {!! Form::close() !!}
                                                                                              @endcan
                                            </span>
                                        </td>
                                    @endif
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
