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
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('project.show',$procurement_date->project->id)}}" class="btn btn-info showToolTip"
                           title="Edit Project" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Project</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($procurement_date,['route'=>['procurement_date.update', $procurement_date->id],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-edit"></span> Edit Procurement Date</h3>
                                {!! minimizeButton('pro_collapse_procurement_date') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_procurement_date">
                                <!-- company_name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('company_name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('company_name','Bank/Insurance Company Name') !!}
                                        {!! Form::text('company_name', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('company_name'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="company_nameStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('company_name') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="company_nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- company_branch -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('company_branch')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('company_branch','Bank/Insurance Company Branch') !!}
                                        {!! Form::text('company_branch', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('company_branch'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="company_branchStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('company_branch') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="company_branchStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            
                                
                                <!-- start_date -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('start_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                    {!! Form::label('start_date','Start Date') !!}
                                    {!! Form::text('start_date', null, ['class'=>'form-control nepaliDate']) !!}
                                    @if($errors->has('start_date'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                    <span id="start_dateStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('start_date') !!}</small></div>
                                    @elseif(count($errors->all())>0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                    <span id="start_dateStatus" class="sr-only">(success)</span>
                                    @endif
                                    </div>
                                </div>
                             <!-- end_date -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('end_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('end_date','End Date') !!}
                                        {!! Form::text('end_date', null, ['class'=>'form-control nepaliDate']) !!}
                                        @if($errors->has('end_date'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="end_dateStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('end_date') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="end_dateStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- ref_no -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('reference_number')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('reference_number','Reference Number') !!}
                                        {!! Form::text('reference_number', null, ['id'=>'reference_number','class'=>'form-control']) !!}
                                        @if($errors->has('reference_number'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="reference_numberStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('reference_number') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="reference_numberStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- amount -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('amount')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('amount','Amount') !!}
                                        {!! Form::text('amount', null, ['class'=>'form-control']) !!}
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
                                
                                <!-- type -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('type','Type') !!}
                                        {!! Form::select('type', procurementDates() ,null, ['class'=>'form-control']) !!}
                                        @if($errors->has('type'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="typeStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('type') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="typeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                @if($procurement_date->file)
                                <div class="form-group col-md-6 col-lg-6">
                                    <div class="input-group pro_make_full showToolTip">
                                        @if(strrchr($procurement_date->file,'.') == '.pdf')
                                            <a class="btn btn-danger" target="_blank" title="Click to view file"
                                               href="{{asset('public/activityFiles/'.$procurement_date->file)}}">
                                                <i class="fa fa-file-pdf-o"></i> View PDF File
                                            </a>
                                        @else
                                            <a target="_blank" title="Click to view file"
                                               href="{{asset('public/activityFiles/'.$procurement_date->file)}}">
                                                <img src="{{asset('public/activityFiles/'.$procurement_date->file)}}"
                                                     alt="Image" class="img-thumbnail" style="width: 25%">
                                            </a>
                                        @endif
                                    </div>
                                    {!! Form::checkbox('remove_current_file', '0',false) !!} Remove Current File
                                </div>
                                @endif
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    {!! Form::file('file') !!}New file (Optional)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_procurement_date_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_procurement_date_save">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save</button>
                                <button class="btn btn-default" type="reset">Reset</button>
                            </div>
                            <div class="panel-footer">
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