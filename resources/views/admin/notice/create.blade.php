@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css" />
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('notice.index')}}"><i class="fa fa-comments"></i> Notice</a></li>
                    <li class="active"><a href="{{route('notice.create')}}"><i class="fa fa-plus"></i> Add Notice</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('notice.index')}}" class="btn btn-info showToolTip" title="Edit Notices" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> Show Notices</a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'notice.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-sm hidden-xs">Add Notice</span></h3>
                                {!! minimizeButton('pro_collapse_notice') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_notice">
                                <!-- name -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('name')) title="{!!$errors->first('name')!!}" @endif>
                                        {!! Form::label('name','Name:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control firstInput','required'=>'required']) !!}
                                        @if($errors->has('name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('description')) title="{!!$errors->first('description')!!}" @endif>
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control ckeditor', 'rows'=>9]) !!}
                                        @if($errors->has('description'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Notice Listener</h3>
                                {!! minimizeButton('pro_collapse_notice_listner') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_notice_listner">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="radio" name="listener" value="publicUsers" checked id="publicUsers" /> Public <br/>
                                    <input type="radio" name="listener" value="contractor" id="contractor"/> Contractor <br/>
                                    <input type="radio" name="listener" value="implementingOffice" id="implementingOffice"/> Implementing Office
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_notice_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_notice_save">
                                <!-- status -->
                                `<div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" checked="checked" />
                                    <label for="status">Is Active?</label>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Next</button>
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
    <script src="{{asset('public/admin/plugin/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/select2/js/select2.full.min.js')}}"></script>
@stop