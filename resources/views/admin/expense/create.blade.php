@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('expense.index')}}"><i class="fa fa-buysellads"></i> Expense</a></li>
                    <li class="active"><a href="{{route('expense.create')}}"><i class="fa fa-plus"></i> Add Expense</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('expense.index')}}" class="btn btn-info showToolTip" title="Edit Expense" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Expenses</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'expense.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Expense</h3>
                                {!! minimizeButton('pro_collapse_expense') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_expense">
                                <!-- voucher_no -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('voucher_no')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('voucher_no','Voucher No:') !!}
                                        {!! Form::text('voucher_no', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('voucher_no'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="voucher_noStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('voucher_no') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="voucher_noStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
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



                                <!-- vat -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('vat')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('vat','Vat Amount:') !!}
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">NRS</span>
                                            {!! Form::text('vat', null, ['class'=>'form-control','aria-describedby'=>'basic-addon1']) !!}
                                        </div>
                                        @if($errors->has('vat'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="vatStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('vat') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="vatStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- tds -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('tds')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('tds','TDS:') !!}
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">NRS</span>
                                            {!! Form::text('tds', null, ['class'=>'form-control','aria-describedby'=>'basic-addon1']) !!}
                                        </div>
                                        @if($errors->has('tds'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="tdsStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('tds') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="tdsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>


                                <!-- paid_to -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('paid_to')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('paid_to','Concerned To:') !!}
                                        {!! Form::select('paid_to',['0'=>'General','1'=>'Registered Vendor','2'=>'Employee'], null, ['class'=>'form-control']) !!}
                                        @if($errors->has('paid_to'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="paid_toStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('paid_to') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="paid_toStatus" class="sr-only">(success)</span>
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


                                <!-- title -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('title','Title:') !!}
                                        {!! Form::text('title', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('title'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="titleStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('title') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="titleStatus" class="sr-only">(success)</span>
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
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Receiver Detail</h3>
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
                                                {!! Form::select('paid_by', ['0'=>'Cash','1'=>'Cheque','2'=>'Bank Transfer'], 0,['class'=>'form-control']) !!}
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
                                        <div class="form-group col-md-6 col-lg-6 @if($errors->has('cheque_no')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                            <div class="input-group pro_make_full showToolTip">
                                                {!! Form::label('cheque_no','Cheque No:') !!}
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

                                        <!-- cheque_name -->
                                        <div class="form-group col-md-6 col-lg-6 @if($errors->has('cheque_name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                            <div class="input-group pro_make_full showToolTip">
                                                {!! Form::label('cheque_name','Name on Cheque:') !!}
                                                {!! Form::text('cheque_name', null, ['class'=>'form-control']) !!}
                                                @if($errors->has('cheque_name'))
                                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="cheque_nameStatus" class="sr-only">(error)</span>
                                                    <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('cheque_name') !!}</small></div>
                                                @elseif(count($errors->all())>0)
                                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="cheque_nameStatus" class="sr-only">(success)</span>
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
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span> Expenditure Topic</h3>
                                {!! minimizeButton('pro_collapse_tree') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_tree">
                                <ul class="tree">
                                    <li>
                                        <div class="click_menu click_menu_active" data-menu-id="1" data-menu-level="0" data-menu-order="0">
                                            <span class="glyphicon glyphicon-th"></span> Root
                                        </div>
                                        <ul>
                                            @foreach($expenditure_topics as $index=>$expenditure_topic)
                                                <?php $voucher_no = $voucher_count[$index] +1;?>
                                                @if($expenditure_topic->level == 1)
                                                    <li>
                                                        <div class="click_menu" data-voucher_no="{{$voucher_no}}" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                            <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
                                                        </div>
                                                        @if(count($expenditure_topic->child)>0)
                                                            <ul>
                                                                @foreach($expenditure_topic->child as $expenditure_topic)
                                                                    @if($expenditure_topic->level == 2)
                                                                        <li>
                                                                            <div class="click_menu" data-voucher_no="{{$voucher_no}}" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                                                <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
                                                                            </div>
                                                                            @if(count($expenditure_topic->child)>0)
                                                                                <ul>
                                                                                    @foreach($expenditure_topic->child as $expenditure_topic)
                                                                                        @if($expenditure_topic->level == 3)
                                                                                            <li>
                                                                                                <div class="click_menu" data-voucher_no="{{$voucher_no}}" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                                                                    <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
                                                                                                </div>
                                                                                                @if(count($expenditure_topic->child)>0)
                                                                                                    <ul>
                                                                                                        @foreach($expenditure_topic->child as $expenditure_topic)
                                                                                                            @if($expenditure_topic->level == 4)
                                                                                                                <li>
                                                                                                                    <div class="click_menu" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                                                                                        <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
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
                                <a href="{{route('expendituretopic.create')}}"
                                   class="btn pull-right btn-success" target="_blank"><i class="fa fa-plus-square-o"></i> Add New Topic</a>
                                <div class="clearfix"></div>
                                </div>

                                {!! Form::hidden('expenditure_topic_id','1',['id'=>'expenditure_topic_id']) !!}
                                {!! Form::hidden('level','1',['id'=>'level']) !!}
                            </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Image</h3>
                                {!! minimizeButton('pro_collapse_expense_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_expense_image">
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
                                {!! minimizeButton('pro_collapse_expense_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_expense_save">
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
            $('.employeeField').hide();
            $('.chequeFields').hide();
            $('.fundStoreField').hide();
            $('.transferCodeField').hide();

            $('#amount').change(function(){
                var $amount = $(this).val();
                var $vat = $amount * 0.13;
                var $tds = $amount * 0.015;
                $amount = Math.floor($amount);
                $vat = Math.ceil($vat);
                $tds = Math.ceil($tds);
                $('#amount').val($amount);
                $('#vat').val($vat);
                $('#tds').val($tds);
            });
            $('#amount').blur(function(){
                var $amount = $(this).val();
                var $vat = $amount * 0.13;
                var $tds = $amount * 0.015;
                $amount = Math.floor($amount);
                $vat = Math.ceil($vat);
                $tds = Math.ceil($tds);
                $('#amount').val($amount);
                $('#vat').val($vat);
                $('#tds').val($tds);
            });

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

            $('#paid_to').change(function(){
               if($(this).val()== 0){
                   $('.vendorField').hide();
                   $('.employeeField').hide();
               }else if($(this).val()== 1){
                    $('.employeeField').hide();
                    $('.vendorField').show();
                }else{
                    $('.vendorField').hide();
                    $('.employeeField').show();
                }
            });

        });
        function reCalculate(){
            var $amount = ('#amount');
            var $vat = ('#vat');
            var $tds = ('#tds');

            $amount = Math.floor($amount);
            $vat = Math.ceil($vat);
            $tds = Math.ceil($tds);
            $('#amount').val($amount);
            $('#vat').val($vat);
            $('#tds').val($tds);
        }
    </script>
@stop