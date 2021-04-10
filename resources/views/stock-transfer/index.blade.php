@extends('layouts.admin')
@section('page-title')
    {{__('Stock Transfer')}}
@endsection

@section('action-button')
    <div class="row d-flex justify-content-end">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
            <div class="all-button-box">
                <a href="{{ route('stock-transfer.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
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
                    {{ Form::close() }}
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Transfer ID')}}</th>
                                <th> {{__('Transfer Date')}}</th>
                                <th>{{__('From Warehouse')}}</th>
                                <th>{{__('To Warehouse')}}</th>
                                <th>{{__('Product')}}</th>
                                <th class="text-right">{{__('Quantity')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($transfers as $transfer)
                                @php $detail = \App\StockTransaction::with('warehouse', 'item')
                                                                ->where('reference_type', \App\Commons::TRANSFER)
                                                                ->where(function($q) use ($transfer) {
                                                                    $q->where('id', $transfer->id)
                                                                      ->orWhere('referee_id', $transfer->id);
                                                                })->orderBy('id')
                                                                  ->get();
                                 @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('stock-transfer.edit',\Crypt::encrypt($transfer->id)) }}">
                                            {{ Auth::user()->transferNumberFormat($transfer->id) }}
                                        </a>
                                    </td>
                                    <td class="Id">{{ Auth::user()->dateFormat($transfer->dated) }}</td>
                                    <td>{{ $detail[0]->warehouse->name }}</td>
                                    <td>{{ $detail[1]->warehouse->name }}</td>
                                    <td>{{ $detail[0]->item->name }}</td>
                                    <td class="text-right">{{ abs($detail[0]->quantity) }}</td>

                                    <td class="Action">
                                            <span>
                                                <a href="{{ route('stock-transfer.edit',Crypt::encrypt($transfer->id)) }}"
                                                       class="edit-icon" data-toggle="tooltip"
                                                       data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                    <a href="#" class="delete-icon " data-toggle="tooltip"
                                                       data-original-title="{{__('Delete')}}"
                                                       data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}"
                                                       data-confirm-yes="document.getElementById('delete-form-{{$transfer->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['stock-transfer.destroy', $transfer->id],'id'=>'delete-form-'.$transfer->id]) !!}
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