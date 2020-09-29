@extends('layouts.admin_layout')
@section('headerContent')
    {{--<link href="{{ asset('public/admin/css/pro_map.css') }}" rel="stylesheet" type="text/css">--}}
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css"/>
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('imports')}}"><span class="glyphicon glyphicon-blackboard"></span> Imports</a>
                    </li>
                    <li><a href="{{route('project.index')}}"><i class="fa fa-buysellads"></i> Project</a></li>
                </ol>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Import Projects</h3>
                                {!! minimizeButton('pro_collapse_project_import') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_project_import">
                                {!!Form::open(['route'=>'project.postImport','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Import the file? Wrong formatting in the file may damage your old data.')"])!!}
                                        <!-- projects -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('projects')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('projects')) title="{!!$errors->first('projects')!!}" @endif>
                                        {!! Form::label('projects','Import File') !!}
                                        {!! Form::File('projects', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('projects'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="projectsStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="projectsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-success" data-loading-text="Importing..." autocomplete="off">
                                    Import
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Import Procurements</h3>
                                {!! minimizeButton('pro_collapse_procurement_import') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_procurement_import">
                                {!!Form::open(['route'=>'procurement.postImport','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Import the file? Wrong formatting in the file may damage your old data.')"])!!}
                                        <!-- procurements -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('procurements')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('procurements')) title="{!!$errors->first('procurements')!!}" @endif>
                                        {!! Form::label('procurements','Import File') !!}
                                        {!! Form::File('procurements', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('procurements'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="procurementsStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="procurementsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-success" data-loading-text="Importing..." autocomplete="off">
                                    Import
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Import Progress</h3>
                                {!! minimizeButton('pro_collapse_progress_import') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_progress_import">
                                {!!Form::open(['route'=>'progress.postImport','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Import the file? Wrong formatting in the file may damage your old data.')"])!!}
                                        <!-- progress -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('progress')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('progress')) title="{!!$errors->first('progress')!!}" @endif>
                                        {!! Form::label('progress','Import File') !!}
                                        {!! Form::File('progress', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('progress'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="progressStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="procurementsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-success" data-loading-text="Importing..." autocomplete="off">
                                    Import
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Import Allocation</h3>
                                {!! minimizeButton('pro_collapse_allocation_import') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_allocation_import">
                                {!!Form::open(['route'=>'allocation.postImport','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Import the file? Wrong formatting in the file may damage your old data.')"])!!}
                                        <!-- allocation -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('allocation')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('progress')) title="{!!$errors->first('allocation')!!}" @endif>
                                        {!! Form::label('allocation','Import File') !!}
                                        {!! Form::File('allocation', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('allocation'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="allocationStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="allocationStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-success" data-loading-text="Importing..." autocomplete="off">
                                    Import
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Import Vope</h3>
                                {!! minimizeButton('pro_collapse_vope_import') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_vope_import">
                                {!!Form::open(['route'=>'vope.postImport','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Import the file? Wrong formatting in the file may damage your old data.')"])!!}
                                        <!-- allocation -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('vope')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('vope')) title="{!!$errors->first('vope')!!}" @endif>
                                        {!! Form::label('vope','Import File') !!}
                                        {!! Form::File('vope', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('vope'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="allocationStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="vopeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-success" data-loading-text="Importing..." autocomplete="off">
                                    Import
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Import Time Extension</h3>
                                {!! minimizeButton('pro_collapse_timeextension_import') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_timeextension_import">
                                {!!Form::open(['route'=>'time_extension.postImport','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Import the file? Wrong formatting in the file may damage your old data.')"])!!}
                                        <!-- allocation -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('time_extension')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('time_extension')) title="{!!$errors->first('time_extension')!!}" @endif>
                                        {!! Form::label('time_extension','Import File') !!}
                                        {!! Form::File('time_extension', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('time_extension'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="allocationStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="time_extensionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-success" data-loading-text="Importing..." autocomplete="off">
                                    Import
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Import Project Group</h3>
                                {!! minimizeButton('group') !!}
                            </div>
                            <div class="panel-body collapse" id="group">
                                {!!Form::open(['route'=>'group.postImport','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Import the file? Wrong formatting in the file may damage your old data.')"])!!}
                                        <!-- group.postImport -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('group')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('group')) title="{!!$errors->first('group')!!}" @endif>
                                        {!! Form::label('group','Import File') !!}
                                        {!! Form::File('group', null, ['class'=>'form-control','required'=>'required']) !!}
                                        @if($errors->has('group'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="groupStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="groupStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-success" data-loading-text="Importing..." autocomplete="off">
                                    Import
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    File Modules</h3>
                                {!! minimizeButton('file_modules') !!}
                            </div>
                            <div class="panel-body collapse" id="file_modules">
                                {!!Form::open(['route'=>'excel.file_modules','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                                <button class="btn btn-success" data-loading-text="Reading File..." autocomplete="off">
                                    Download
                                </button>
                                <button class="btn btn-default" type="reset">Cancel</button>
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

@stop