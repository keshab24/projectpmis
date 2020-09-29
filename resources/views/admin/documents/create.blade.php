@extends('layouts.admin_layout')
@section('headerContent')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <br>
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li>Documents</li>
                </ol>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-8 col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-file"></i> Documents</h3>
                                {!! minimizeButton('pro_collapse_a_log') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_a_log">
                            {!!Form::open(['route'=>['document.store'],'method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                            <!-- category -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('category_id','Document Type:') !!}
                                        {!! Form::select('category_id', documentCategory(), null, ['class'=>'form-control']) !!}
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
                                <!-- title -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('title','Title:') !!}
                                        {!! Form::text('title', null, ['class'=>'form-control']) !!}
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
                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
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
                    {!! Form::close() !!}
                    <div class="clearfix"></div>
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
    </script>
@stop


