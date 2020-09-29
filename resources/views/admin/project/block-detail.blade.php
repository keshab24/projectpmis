@extends('layouts.admin_layout')
@section('headerContent')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@stop

@section('content')
    @php
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
    @endphp
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li><a href="{{route('project.index')}}"><i class="fa fa-buysellads"></i> Projects</a></li>
                    <li><a href="{{route('project.show', $project->id)}}">{{$project->name}}</a></li>
                    <li class="active"><a
                                href="{{route('project-block.detail',[$project->id, $block->id])}}">{{ $block->block_name }} </a>
                    </li>
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('project.show', $project->id)}}"
                           class="btn btn-warning showToolTip"
                           title="Back To Project Detail" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-chevron-left"></span><span class="hidden-xs hidden-sm"> Back</span></a>
                        <a href="{{route('project.index')}}" class="btn btn-info showToolTip"
                           title="View All Projects" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Projects</span></a>
                    </div>
                    <div class="col-md-1 col-lg-1 pull-right">
                        <a class="btn btn-xs btn-primary" title="Logs"
                           href="{{ route('projectLogs',$project->id) }}"><span class="fa fa fa-th-list"></span> View
                            Log</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    {{ $block->block_name }} - Edit</h3>
                                {!! minimizeButton('edit_block') !!}
                            </div>
                            <div class="panel-body collapse in" id="edit_block">
                                {!! Form::model($block,['route'=>['project-block.update',$block->id],'method'=>'PUT']) !!}
                                <div class="row">
                                    <div class="col-sm-6">
                                        {!! Form::text('block_name', null,['class'=>'form-control','required']) !!}
                                    </div>
                                </div>

                                <div class="row " style="margin-top:5px;">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}

                            </div>

                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    {{ $block->block_name }} - Details</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice_save">
                                {!! Form::model($block,['route'=>['project-block.detail.store', $project->id,$block->id]]) !!}
                                <div class="row">
                                    <div class="col-sm-6">
                                        {!! Form::label('structure_type', 'Structure Type') !!}
                                        {!! Form::select('structure_type',bsType(),null,['class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('story_area_unite','भवनको तल्ला:') !!}
                                        {!! Form::select('story_area_unite', getStoreyArea(), $block->story_area_unite, ['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-sm-6">
                                        {!! Form::label('plinth_area','Plinth Area (वर्ग मी.)') !!}
                                        {!! Form::text('plinth_area',null,['class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('floor_area','Floor Area (वर्ग मी.)') !!}
                                        {!! Form::text('floor_area',null,['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-sm-6">
                                        {!! Form::label('Roof Type','छानाको प्रकृति:') !!}
                                        {!! Form::select('roof_type', rooftype(),$block->roof_type, ['class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('Door Window','झ्याल ढोका:') !!}
                                        {!! Form::select('door_window', doorWindow(),$block->door_window, ['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-sm-6">
                                        {!! Form::label('Wall Type','गारोको किसिम :') !!}
                                        {!! Form::select('wall_type', wallType(),$block->wall_type, ['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}

                                </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-file"></i> Activity Log</h3>
                                {!! minimizeButton('pro_collapse_a_log') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_a_log">
                            {!!Form::open(['route'=>['project.upload.log.block', $block->id],'method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                            <!-- title -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('title','Title:') !!}
                                        {!! Form::text('title', null, ['class'=>'form-control']) !!}
                                        {!! Form::hidden('project_id', $project->id) !!}
                                        @if($errors->has('title'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="titleStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('title') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="titleStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('type','Type:') !!}
                                        <br/>
                                        {!! Form::Select('type',progressActivityLogsTypes()['block'],null) !!}
                                    </div>
                                </div>
                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control','required']) !!}
                                        @if($errors->has('description'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('description') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- date -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('date','Date:') !!}
                                        <input type="text" class="form-control" name="date" id="date"
                                               value="{{str_replace('/','-',dateBS(date('Y-m-d')))}}">
                                        @if($errors->has('date'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="dateStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('date') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="dateStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- file -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('file')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('file','File:') !!}
                                        {!! Form::file('file[]', ['class'=>'form-control','multiple'=>true]) !!}
                                        @if($errors->has('file'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="fileStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('file') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="fileStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                    Upload
                                </button>
                                <button class="btn btn-default" type="reset">Reset</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop
@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/editor.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $('#cancelled_modal').on('hidden.bs.modal', function (e) {
                cancelProjectCancellation()
            })
            $('#cancelled').on('change', function (e) {
                if (e.target.checked) {
                    $('#cancelled_modal').modal();
                }
            });
            $('#cancelledForm').submit(function (e) {
                $project_code = $('#project_code').val();
                if ($project_code === $(this).attr('data-project-id')) {
                    $('#project_code_error_on_finish').hide();
                    cancelled_date = $('#cancelled_date').val();
                    if (cancelled_date == '' || cancelled_date == '0000-00-00') {
                        $('#cancelled_date_error_on_finish').show();
                        $('#cancelled_date_error_on_finish').html('Enter Valid Date');
                        return false;
                    } else {
                        $('#cancelled_date_error_on_finish').hide();
                    }
                    cancelled_remarks = $('#cancelled_remarks').val();
                    if (cancelled_remarks == '' || cancelled_remarks == '0000-00-00') {
                        $('#cancelled_remarks_error_on_finish').show();
                        $('#cancelled_remarks_error_on_finish').html('Please Enter Project Cancelled remark');
                        return false;
                    } else {
                        $('#cancelled_remarks_error_on_finish').hide();
                    }
                } else {
                    $('#project_code_error_on_finish').show();
                    $('#project_code_error_on_finish').html('Enter Valid Project Code to Proceed');
                    return false;
                }
                return true
            })

            $('#date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
        });

        function cancelProjectCancellation() {
            $('#cancelled').prop('checked', false)
        }
    </script>

@stop


