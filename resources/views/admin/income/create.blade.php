@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('income.index')}}"><i class="fa fa-buysellads"></i> Income</a></li>
                    <li class="active"><a href="{{route('income.create')}}"><i class="fa fa-plus"></i> Add Income</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('income.index')}}" class="btn btn-info showToolTip" title="Edit Income" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Incomes</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'income.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Income</h3>
                                {!! minimizeButton('pro_collapse_income') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_income">

                                <!-- amount -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('amount')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">

                                        {!! Form::label('amount','Amount:') !!}
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">NRS</span>
                                            {!! Form::text('amount', null, ['class'=>'form-control firstInput','aria-describedby'=>'basic-addon1']) !!}
                                        </div>
                                        @if($errors->has('amount'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="amountStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('amount') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="amountStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- earned_from -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('earned_from')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('earned_from','Concerned To:') !!}
                                        {!! Form::select('earned_from',['0'=>'General','1'=>'Registered Vendor','2'=>'Employee','3'=>'Implementing Office'], null, ['class'=>'form-control']) !!}
                                        @if($errors->has('earned_from'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="earned_fromStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('earned_from') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="earned_fromStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- vendor_id -->
                                <div class="form-group col-md-12 col-lg-12 vendorField @if($errors->has('vendor_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('vendor_id','Vendor:') !!}
                                        {!! Form::select('vendor_id', $vendors,0, ['class'=>'form-control']) !!}
                                        @if($errors->has('vendor_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="vendor_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('vendor_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="vendor_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- employee_id -->
                                <div class="form-group col-md-12 col-lg-12 employeeField @if($errors->has('employee_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('employee_id','Employee:') !!}
                                        {!! Form::select('employee_id',$employees, 0, ['class'=>'form-control']) !!}
                                        @if($errors->has('employee_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="employee_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('employee_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="employee_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- implementing_office_id -->
                                <div class="form-group col-md-12 col-lg-12 implementingOfficeField @if($errors->has('implementing_office_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('implementing_office_id','Implementing Ofice:') !!}
                                        {!! Form::select('implementing_office_id',$implementing_offices, 0, ['class'=>'form-control']) !!}
                                        @if($errors->has('implementing_office_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="implementing_office_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('implementing_office_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="implementing_office_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>



                                
                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Statement:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>4]) !!}
                                        @if($errors->has('description'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('description') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Received From</h3>
                                {!! minimizeButton('pro_collapse_receiver') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_receiver">
                                <!-- receiver_name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('receiver_name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('receiver_name','Name:') !!}
                                        {!! Form::text('receiver_name', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('receiver_name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="receiver_nameStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('receiver_name') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="receiver_nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- receiver_contact -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('receiver_contact')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('receiver_contact','Contact:') !!}
                                        {!! Form::text('receiver_contact', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('receiver_contact'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="receiver_contactStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('receiver_contact') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="receiver_contactStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- receiver_address -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('receiver_address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('receiver_address','Address:') !!}
                                        {!! Form::text('receiver_address', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('receiver_address'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="receiver_addressStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('receiver_address') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="receiver_addressStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Payment Method</h3>
                                {!! minimizeButton('pro_collapse_payment') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_payment">
                                <div class="col-md-12 col-lg-12">
                                    <div class="row">
                                        <!-- paid_by -->
                                        <div class="form-group col-md-6 col-lg-6 @if($errors->has('paid_by')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                            <div class="input-group pro_make_full showToolTip">
                                                {!! Form::label('paid_by','Paid By:') !!}
                                                {!! Form::select('paid_by', ['0'=>'Cash','1'=>'Bank Voucher','2'=>'Bank Transfer'], 0,['class'=>'form-control']) !!}
                                                @if($errors->has('paid_by'))
                                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="paid_byStatus" class="sr-only">(error)</span>
                                                    <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('paid_by') !!}</small></div>
                                                @elseif(count($errors->all())>0)
                                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="paid_byStatus" class="sr-only">(success)</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- fund_store_id -->
                                        <div class="form-group col-md-6 col-lg-6 fundStoreField @if($errors->has('fund_store_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                            <div class="input-group pro_make_full showToolTip">
                                                {!! Form::label('fund_store_id','Bank:') !!}
                                                {!! Form::select('fund_store_id', $fundStores,0, ['class'=>'form-control']) !!}
                                                @if($errors->has('fund_store_id'))
                                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="fund_store_idStatus" class="sr-only">(error)</span>
                                                    <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('fund_store_id') !!}</small></div>
                                                @elseif(count($errors->all())>0)
                                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="fund_store_idStatus" class="sr-only">(success)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row chequeFields">
                                        <!-- cheque_no -->
                                        <div class="form-group col-md-12 col-lg-12 @if($errors->has('cheque_no')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                            <div class="input-group pro_make_full showToolTip">
                                                {!! Form::label('cheque_no','Voucher No:') !!}
                                                {!! Form::text('cheque_no', null, ['class'=>'form-control']) !!}
                                                @if($errors->has('cheque_no'))
                                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="cheque_noStatus" class="sr-only">(error)</span>
                                                    <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('cheque_no') !!}</small></div>
                                                @elseif(count($errors->all())>0)
                                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="cheque_noStatus" class="sr-only">(success)</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- transfer_code -->
                                <div class="form-group col-md-12 col-lg-12 transferCodeField @if($errors->has('transfer_code')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('transfer_code','Transfer Code:') !!}
                                        {!! Form::text('transfer_code', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('transfer_code'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="transfer_codeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('transfer_code') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="transfer_codeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span> Income Topic</h3>
                                {!! minimizeButton('pro_collapse_tree') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_tree">
                                <ul class="tree">
                                    <li>
                                        <div class="click_menu click_menu_active" data-menu-id="1" data-menu-level="0" data-menu-order="0">
                                            <span class="glyphicon glyphicon-th"></span> Root
                                        </div>
                                        <ul>
                                            @foreach($income_topics as $income_topic)
                                                @if($income_topic->level == 1)
                                                    <li>
                                                        <div class="click_menu" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                            <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
                                                        </div>
                                                        @if(count($income_topic->child)>0)
                                                            <ul>
                                                                @foreach($income_topic->child as $income_topic)
                                                                    @if($income_topic->level == 2)
                                                                        <li>
                                                                            <div class="click_menu" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                                                <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
                                                                            </div>
                                                                            @if(count($income_topic->child)>0)
                                                                                <ul>
                                                                                    @foreach($income_topic->child as $income_topic)
                                                                                        @if($income_topic->level == 3)
                                                                                            <li>
                                                                                                <div class="click_menu" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                                                                    <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
                                                                                                </div>
                                                                                                @if(count($income_topic->child)>0)
                                                                                                    <ul>
                                                                                                        @foreach($income_topic->child as $income_topic)
                                                                                                            @if($income_topic->level == 4)
                                                                                                                <li>
                                                                                                                    <div class="click_menu" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                                                                                        <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
                                                                                                                    </div>
                                                                                                                </li>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                @endif
                                                                                            </li>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                                <br />
                                <br />
                                <a href="{{route('income-topic.create')}}"
                                   class="btn pull-right btn-success" target="_blank"><i class="fa fa-plus-square-o"></i> Add New Topic</a>
                                <div class="clearfix"></div>
                                </div>

                                {!! Form::hidden('income_topic_id','1',['id'=>'income_topic_id']) !!}
                                {!! Form::hidden('level','1',['id'=>'level']) !!}
                            </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Image</h3>
                                {!! minimizeButton('pro_collapse_income_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_income_image">
                                <!-- image -->
                                <label class="drop-zone form-group col-md-12 col-lg-12 @if($errors->has('image')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" for="image_browse">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('image')) title="{!!$errors->first('image')!!}" @endif>
                                        <span>Click here to select a image.</span>
                                        {!!Form::file('image', ['class'=>'form-control','id'=>'image_browse'])!!}
                                        @if($errors->has('image'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="imageStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="imageStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                    <img src="" id="image_preview" alt=""/>
                                </label>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_income_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_income_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" checked="checked" />
                                    <label for="status">Is Active?</label>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save</button>
                                <button class="btn btn-default" type="reset">Reset</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $('.vendorField').hide();
            $('.implementingOfficeField').hide();
            $('.employeeField').hide();
            $('.chequeFields').hide();
            $('.fundStoreField').hide();
            $('.transferCodeField').hide();

            $('#paid_by').change(function() {
                if ($(this).val() == 0) {
                    $('.chequeFields').hide();
                    $('.fundStoreField').hide();
                    $('.transferCodeField').hide();
                }else if ($(this).val() == 1){
                    $('.chequeFields').show();
                    $('.fundStoreField').show();
                    $('.transferCodeField').hide();
                }else{
                    $('.chequeFields').hide();
                    $('.fundStoreField').show();
                    $('.transferCodeField').show();
                }
            });

            $('#earned_from').change(function(){
                if($(this).val()== 0){
                   $('.vendorField').hide();
                   $('.employeeField').hide();
                   $('.implementingOfficeField').hide();
                }else if($(this).val()== 1){
                    $('.employeeField').hide();
                    $('.implementingOfficeField').hide();
                    $('.vendorField').show();
                }else if($(this).val()== 2){
                   $('.vendorField').hide();
                   $('.implementingOfficeField').hide();
                   $('.employeeField').show();
                }else{
                    $('.vendorField').hide();
                    $('.implementingOfficeField').show();
                    $('.employeeField').hide();
                }
            });
        });
    </script>
@stop