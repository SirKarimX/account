@extends('layouts.admin')
@section('page-title')
    {{__('Stock Report')}}
@endsection
@push('script-page')
    <script src="{{ asset('assets/js/jspdf.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jszip.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/pdfmake.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/dataTables.buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/buttons.html5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/buttons.print.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A4'}
            };
            html2pdf().set(opt).from(element).save();
        }

        $(document).ready(function () {
            var filename = $('#filename').val();
            $('#report-dataTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: filename
                    },
                    {
                        extend: 'pdf',
                        title: filename
                    }, {
                        extend: 'csv',
                        title: filename
                    }
                ]
            });
        });
    </script>
@endpush

@section('action-button')
    <div class="row d-flex justify-content-end">
        <div class="col-auto">
            {{ Form::open(array('route' => array('report.stock'),'method'=>'get','id'=>'report_stock')) }}
            <div class="all-select-box">
                <div class="btn-box">
                    {{Form::label('start_month',__('Start Month'),['class'=>'text-type'])}}
                    {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:date('Y-m'),array('class'=>'month-btn form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="all-select-box">
                <div class="btn-box">
                    {{Form::label('end_month',__('End Month'),['class'=>'text-type'])}}
                    {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:date('Y-m'),array('class'=>'month-btn form-control'))}}
                </div>
            </div>
        </div>
        <div class="col">
            <div class="all-select-box">
                <div class="btn-box">
                    {{Form::label('warehouse',__('Warehouse'),['class'=>'text-type'])}}
                    {{Form::select('warehouse', $warehouses,isset($_GET['warehouse'])?$_GET['warehouse']:'', array('class' => 'form-control select2')) }}
                </div>
            </div>
        </div>
        <div class="col">
            <div class="all-select-box">
                <div class="btn-box">
                    {{ Form::label('product', __('Product'),['class'=>'text-type']) }}
                    {{ Form::select('product',$product_services,isset($_GET['product'])?$_GET['product']:'', array('class' => 'form-control select2')) }}
                </div>
            </div>
        </div>
        <div class="col-auto my-auto">
            <a href="#" class="apply-btn" onclick="document.getElementById('report_stock').submit(); return false;"
               data-toggle="tooltip" data-original-title="{{__('apply')}}">
                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            </a>
            <a href="{{route('report.stock')}}" class="reset-btn" data-toggle="tooltip"
               data-original-title="{{__('Reset')}}">
                <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
            </a>
            <a href="#" class="action-btn" onclick="saveAsPDF()" data-toggle="tooltip"
               data-original-title="{{__('Download')}}">
                <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
            </a>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@section('content')
    <div id="printableArea">
        <div class="row mt-3">
            <div class="col">
                <input type="hidden"
                       value="{{__('Stock Report').' from '.$filter['startDateRange'].' to '.$filter['endDateRange']}}"
                       id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h5 class="report-text mb-0">{{__('Stock Report')}}</h5>
                </div>
            </div>
            @if($filter['warehouse']!=__('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Warehouse')}} :</h5>
                        <h5 class="report-text mb-0">{{$filter['warehouse']}}</h5>
                    </div>
                </div>
            @endif
            @if($filter['product']!=__('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Product')}} :</h5>
                        <h5 class="report-text mb-0">{{$filter['product']}}</h5>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h5 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive pt-4">
                        <table class="table table-striped mb-0" id="report-dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Warehouse')}}</th>
                                <th>{{__('Product')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Ref No.')}}</th>
                                <th>{{__('Description')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($reportData as $data)
                                @continue($data->reference_type==\App\Commons::TRANSFER && $data->referee_id!=0)
                                @php


                                    $reference_no = '';
                                    if($data->reference_type == \App\Commons::INVOICE) {
                                        $reference_no = '<a href="' . route('invoice.show',\Crypt::encrypt($data->reference_id)) . '">'
                                                . Auth::user()->invoiceNumberFormat($data->invoice->invoice_id) . '</a>';
                                    }
                                    else if($data->reference_type == \App\Commons::BILL) {
                                        $reference_no = '<a href="' . route('bill.show',\Crypt::encrypt($data->reference_id)) . '">'
                                                . Auth::user()->billNumberFormat($data->bill->bill_id) . '</a>';
                                    }
                                    else if($data->reference_type == \App\Commons::TRANSFER) {
                                        $reference_no = '<a href="' . route('stock-transfer.edit',\Crypt::encrypt($data->reference_id)) . '">'
                                                . Auth::user()->transferNumberFormat($data->reference_id) . '</a>';
                                    }
                                     else if($data->reference_type == \App\Commons::ADJUST) {
                                        $reference_no = '<a href="' . route('stock-adjustment.edit',\Crypt::encrypt($data->reference_id)) . '">'
                                                . Auth::user()->adjustmentlNumberFormat($data->reference_id) . '</a>';
                                    }
                                    @endphp
                                <tr class="font-style">
                                    {{--<td>{{ Auth::user()->priceFormat($data->amount) }}</td>--}}
                                    <td>{{ Auth::user()->dateFormat($data->dated) }}</td>
                                    <td>{{ $data->warehouse->name }}</td>
                                    <td>{{ $data->item->name }}</td>
                                    <td>{{ $data->quantity }}</td>
                                    <td>{{ \App\Commons::TRANSACTION_TYPES[$data->reference_type] }}</td>
                                    <td class="Id">{!! $reference_no !!}</td>
                                    <td>{{!empty($data->description)?$data->description:'-'}} </td>
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
