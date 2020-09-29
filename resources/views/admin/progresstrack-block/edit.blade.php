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
                    <li><a href="{{route('progresstrack-block.index',$project->id)}}"><i class="fa fa-buysellads"></i> Progress Track</a></li>
                    <li class="active"><a href="#"><i class="fa fa-edit"></i> Edit Progress Track</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('progresstrack-block.index', $project->id)}}" class="btn btn-info showToolTip" title="Edit Progress Track" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Progress Tracks</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($progresstrack,['route'=>['progresstrack-block.update',$project->id,$progresstrack->id],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Progress Track</h3>
                                {!! minimizeButton('pro_collapse_progresstrack') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_progresstrack">
                                <!-- progress_type -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('progress_type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('progress_type','Choose Type:') !!}
                                        {!! Form::select('progress_type', $progress_types, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('progress_type'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="progress_typeStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('progress_type') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="progress_typeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('block_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('block_id','Choose Block:') !!}
                                        {!! Form::select('block_id', $project_blocks, $progresstrack->block_id, ['class'=>'form-control','required']) !!}
                                        @if($errors->has('block_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="progress_typeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('block_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="block_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- progress -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('progress')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('progress','Progress:') !!}
                                        {!! Form::text('progress', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('progress'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="progressStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('progress') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="progressStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- progress_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('progress_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('progress_eng','Progress in English:') !!}
                                        {!! Form::text('progress_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('progress_eng'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="progress_engStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('progress_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="progress_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- physical_percentage -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('physical_percentage')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('physical_percentage','Physical Percentage:') !!}
                                        {!! Form::text('physical_percentage', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('physical_percentage'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="physical_percentageStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('physical_percentage') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="physical_percentageStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- storey_area -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('storey_area')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('storey_area')) title="{!!$errors->first('storey_area')!!}" @endif>
                                        {!! Form::label('storey_area','Storey Area') !!}
                                        {!! Form::select('storey_area', getStoreyArea(), null, ['class'=>'form-control']) !!}
                                        @if($errors->has('storey_area'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="storey_areaStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="storey_areaStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- monitoring_office_id -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('monitoring_office_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('monitoring_office_id')) title="{!!$errors->first('monitoring_office_id')!!}" @endif>
                                        {!! Form::label('monitoring_office_id','Monitoring Ofice') !!}
                                        {!! Form::select('monitoring_office_id', $monitoring_offices, $progresstrack->monitoring_office_id, ['class'=>'form-control']) !!}
                                        @if($errors->has('monitoring_office_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="monitoring_office_idStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="monitoring_office_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Image</h3>
                                {!! minimizeButton('pro_collapse_progresstrack_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_progresstrack_image">
                                <!-- image -->
                                <label class="drop-progresstrack form-group col-md-12 col-lg-12 @if($errors->has('image')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" for="image_browse">
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
                                {!! minimizeButton('pro_collapse_progresstrack_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_progresstrack_save">
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

        });
    </script>
@stop