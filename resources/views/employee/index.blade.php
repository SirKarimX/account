@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
    {{__('Manage Employees')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create user')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-size="2xl" data-url="{{ route('employee.create') }}" data-ajax-popup="true" data-title="{{__('Create New Payment')}}" class="btn btn-xs btn-white btn-icon-only width-auto commonModal">
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
                                
                                <th> {{__('Name')}}</th>
                                <th> {{__('Role')}}</th>
                                <th> {{__('Salary')}}</th>
                                <th> {{__('Target')}}</th>
                                <th> {{__('Balance')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            
                            @foreach ($users as $k=>$user)
               @php

 $totalInvoicePaymentSum=$user->userTotalPaymentInvoice($user['id']);
                      
                          $TotalPaymentSum=$user->userTotalPaymentSum($user['id']);
                           $balancePayment=$user['salary'] - $TotalPaymentSum;
                        $TotalCommission=$totalInvoicePaymentSum*$user['commission']/100;
                    @endphp        
                                <tr>
                                    <td class="Id">
                                      
                                                   {{ $user['name'] }}
                                    
                                    </td>

                                   
                                    <td>{{$user['type']}}</td>
                                    <td>{{\Auth::user()->priceFormat($user['salary'])}}</td>
                                    <td>{{\Auth::user()->priceFormat($totalInvoicePaymentSum)}} of {{\Auth::user()->priceFormat($user['target'])}}</td>
                                        <td>@if($totalInvoicePaymentSum>=$user['target'])
                                {{\Auth::user()->priceFormat($balancePayment+$TotalCommission)}}
@else
 {{\Auth::user()->priceFormat($balancePayment)}}
 @endif</td>
                                    <td class="Action">
                                        <span>
                                         
                                                
                                                    <a href="{{ route('employee.show',\Crypt::encrypt($user['id'])) }}" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                         
                                                @can('edit user')
                                                    <a href="#" class="edit-icon" data-size="2xl" data-url="{{ route('employee.edit',$user->id) }}"data-ajax-popup="true" data-title="{{__('Edit Employee')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete user')
                                                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $user['id']}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]) !!}
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
