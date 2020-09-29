@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('budgettopic.index')}}"><i class="fa fa-buysellads"></i> Budget Topic</a></li>
                    <li class="active"><a href="{{route('budgettopic.create')}}"><i class="fa fa-plus"></i> Add Budget Topic</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('budgettopic.index')}}" class="btn btn-info showToolTip" title="Edit Budget Topic" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Budget Topics</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'budgettopic.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Budget Topic</h3>
                                {!! minimizeButton('pro_collapse_budgettopic') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_budgettopic">

                                <!-- budget_head -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('budget_head')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('budget_head','बजेट शीर्षक नाम:') !!}
                                        {!! Form::text('budget_head', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('budget_head'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_headStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('budget_head') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_headStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- budget_head_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('budget_head_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('budget_head_eng','Budget Head Name (English):') !!}
                                        {!! Form::text('budget_head_eng', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('budget_head_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_head_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('budget_head_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_head_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- budget_topic_num -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('budget_topic_num')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('budget_topic_num','ब.उ.शी.नं.:') !!}
                                        {!! Form::text('budget_topic_num', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('budget_topic_num'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_topic_numStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('budget_topic_num') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_topic_numStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- monitoring_office_id -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('priority')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('monitoring_office_id','Monitoring Office:') !!}
                                        {!! Form::select('monitoring_office_id', $monitoringOffice, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('monitoring_office_id'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="monitoring_office_id" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('priority') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="monitoring_office_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- priority -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('priority')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('priority','Priority:') !!}
                                        {!! Form::select('priority', $priorities, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('priority'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="priorityStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('priority') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="priorityStatus" class="sr-only">(success)</span>
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
                                {!! minimizeButton('pro_collapse_budgettopic_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_budgettopic_save">
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

        });
    </script>
@stop