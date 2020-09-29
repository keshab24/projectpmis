@extends('layouts.admin_layout')
@section('headerContent')
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span>
                            Dashboard</a></li>
                    <li><a href="{{route('salaryhead.index')}}"><i class="fa fa-buysellads"></i> Salary head</a>
                    </li>
                    <li class="active"><a href="{{route('salaryhead.create')}}"><i class="fa fa-plus"></i>
                            Add Salary head</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('salaryhead.index')}}" class="btn btn-info showToolTip"
                           title="Edit Salary head" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Salary heads</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'salaryhead.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add
                                    Salary head</h3>
                                {!! minimizeButton('pro_collapse_salaryhead') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_salaryhead">
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('name')) title="{!!$errors->first('name')!!}" @endif>
                                        {!! Form::label('name','Name:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                               <!-- name_nep -->
                               <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_nep')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                   <div class="input-group pro_make_full showToolTip"
                                        @if($errors->has('name_nep')) title="{!!$errors->first('name_nep')!!}" @endif>
                                       {!! Form::label('name_nep','Name Nepali:') !!}
                                       {!! Form::text('name_nep', null, ['class'=>'form-control']) !!}
                                       @if($errors->has('name_nep'))
                                           <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                 aria-hidden="true"></span>
                                           <span id="name_nepStatus" class="sr-only">(error)</span>
                                       @elseif(count($errors->all())>0)
                                           <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                 aria-hidden="true"></span>
                                           <span id="name_nepStatus" class="sr-only">(success)</span>
                                       @endif
                                   </div>
                               </div>
                                <!-- description -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('description')) title="{!!$errors->first('description')!!}" @endif>
                                        {!! Form::label('description','Description English:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>4]) !!}
                                        @if($errors->has('description'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- description_nep -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description_nep')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('description_nep')) title="{!!$errors->first('description_nep')!!}" @endif>
                                        {!! Form::label('description_nep','Description Nepali:') !!}
                                        {!! Form::textarea('description_nep', null, ['class'=>'form-control','rows'=>4]) !!}
                                        @if($errors->has('description_nep'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="description_nepStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="description_nepStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- taxable -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="taxable" id="taxable" class="first_color" />
                                    <label for="taxable">Taxable?</label>
                                </div>
                                <!-- deductable -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="deductable" id="deductable" class="first_color"/>
                                    <label for="deductable">Deductable?</label>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight"
                         data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Publish</h3>
                                {!! minimizeButton('pro_collapse_salaryhead_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_salaryhead_save">
                                <!-- status -->
                                <div class="form-group col-md-3 col-lg-3 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color"
                                           checked="checked"/>
                                    <label for="status">Is Active?</label>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save
                                </button>
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
        $('document').ready(function () {
            $('#date_of_birth').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
            $('#date_of_join').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
        });
    </script>
@stop