@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <style type="text/css">
        .ui-widget{
            font-size: 12px;
        }
    </style>
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('division.index')}}"><i class="fa fa-buysellads"></i> Division</a></li>
                    <li class="active"><a href="{{route('division.create')}}"><i class="fa fa-plus"></i> Add Division</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('division.index')}}" class="btn btn-info showToolTip" title="Edit Division" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Divisions</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'division.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Division</h3>
                                {!! minimizeButton('pro_collapse_division') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_division">
                                <!-- division_code -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('division_code')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('division_code','Choose Division Name (District):') !!}
                                        {!! Form::select('division_code', $districts, null, ['class'=>'form-control']) !!}
                                        {!! Form::select('division_code_eng', $districts_eng, null,['id'=>'division_code_eng','style'=>'display:none']) !!}
                                        @if($errors->has('division_code'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="division_codeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('division_code') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="division_codeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- districts -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('districts')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('districts','Districts:') !!}
                                        {!! Form::text('districts', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('districts'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="districtsStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('districts') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="districtsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','Name:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('name') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- name_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name_eng','Name English:') !!}
                                        {!! Form::text('name_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('name_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('name_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- description -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
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

                                <!-- description_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description_eng','Description English:') !!}
                                        {!! Form::textarea('description_eng', null, ['class'=>'form-control','rows'=>4]) !!}
                                        @if($errors->has('description_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="description_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('description_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="description_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Image</h3>
                                {!! minimizeButton('pro_collapse_district_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_district_image">
                                <!-- image -->
                                <label class="drop-district form-group col-md-12 col-lg-12 @if($errors->has('image')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" for="image_browse">
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
                                {!! minimizeButton('pro_collapse_division_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_division_save">
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
    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $('#division_code').change(function(){
               $('#name').val('डिभिजन कार्यालय, '+$('#division_code option:selected').text());
                $('#division_code_eng').val($(this).val());
               $('#name_eng').val('Division Office, '+$('#division_code_eng option:selected').text());
            });

            var $source = [<?php foreach($districts as $district) echo '"'.$district.'",'; ?>];
            var $districts = $('#districts');
            $districts.tokenfield({
                autocomplete: {
                    source: $source,
                    delay: 100
                },
                createTokensOnBlur:true,
                showAutocompleteOnFocus: true
            });
            preventDuplicate($districts);
        });
    </script>
@stop