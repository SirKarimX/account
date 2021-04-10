<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'warehouse')) }}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Warehouse Name'),['class'=>'form-control-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
            {{ Form::text('description', '', array('class' => 'form-control')) }}
        </div>
        <div class="col-md-12 text-right">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
