@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('lumpsumbudget.index')}}"><i class="fa fa-buysellads"></i> Lump Sum Budget</a></li>
                    <li class="active"><a href="{{route('lumpsumbudget.create')}}"><i class="fa fa-plus"></i> Add Lump Sum Budget</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('lumpsumbudget.index')}}" class="btn btn-info showToolTip" title="Edit Lump Sum Budget" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Lump Sum Budget</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'lumpsumbudget.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Sure with fiscal year you selected?.')"])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Lump Sum Budget <strong class="text-danger"> पुरा बजेट चडाउनुहोस्</strong></h3>
                                {!! minimizeButton('pro_collapse_lumpsumbudget') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_lumpsumbudget">

                                <!-- budget_topic_id -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('budget_topic_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('budget_topic_id','ब.उ.शी.नं.:') !!}
                                        {!! Form::select('budget_topic_id', $budgettopics, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('budget_topic_id'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="budget_topic_idStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('budget_topic_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="budget_topic_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- expenditure_topic_id -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('expenditure_topic_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('expenditure_topic_id','ख.शी.नं. छान्नुहाेस्:') !!}
                                        {!! Form::select('expenditure_topic_id', $expendituretopics, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('expenditure_topic_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_topic_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('expenditure_topic_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_topic_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- running_capital -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('running_capital')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('running_capital','चालु/पूँजीगत छान्नुहाेस्:') !!}
                                        {!! Form::select('running_capital', $runningcapitals, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('running_capital'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="running_capitalStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('running_capital') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="running_capitalStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- total_budget -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('total_budget')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('total_budget','जम्मा बजेट चढाउनुहाेस्:') !!}
                                        {!! Form::text('total_budget', null, ['class'=>'form-control focus_field']) !!}
                                        @if($errors->has('total_budget'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="total_budgetStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('total_budget') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="total_budgetStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- gon -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('gon')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('gon','नेपाल सरकार:') !!}
                                        {!! Form::text('gon', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('gon'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="gonStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('gon') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="gonStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- loan -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('loan')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('loan','ऋृण:') !!}
                                        {!! Form::text('loan', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('loan'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="loanStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('loan') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="loanStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- grants -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('grants')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('grants')) title="{!!$errors->first('grants')!!}" @endif>
                                        {!! Form::label('grants','अनुदान:') !!}
                                        {!! Form::text('grants', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('grants'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="grantsStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="grantsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- direct_payments -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('direct_payments')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('direct_payments','सोझै भुक्तानी:') !!}
                                        {!! Form::text('direct_payments', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('direct_payments'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="direct_paymentsStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('direct_payments') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="direct_paymentsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_lumpsumbudget_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_lumpsumbudget_save">
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
    <script type="text/javascript">
        $('document').ready(function() {
            $("#total_budget").change(function(){
                var $pro_total_budget = $("#total_budget").val();
                $("#gon").val($pro_total_budget);
            });
            $("#gon").change(function(){
                var $pro_donor = $("#total_budget").val()-$("#gon").val();
                $("#loan").val($pro_donor);
            });
            $("#loan").change(function(){
                var $pro_loan = $("#total_budget").val()-$("#gon").val()-$("#loan").val();
                $("#grants").val($pro_loan);
            });
        });
    </script>
@stop