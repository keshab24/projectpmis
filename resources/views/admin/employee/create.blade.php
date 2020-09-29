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
                    <li><a href="{{route('employee.index')}}"><i class="fa fa-buysellads"></i> Employee</a>
                    </li>
                    <li class="active"><a href="{{route('employee.create')}}"><i class="fa fa-plus"></i>
                            Add Employee</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('employee.index')}}" class="btn btn-info showToolTip"
                           title="Edit Employee" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Employees</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'employee.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add
                                    Employee</h3>
                                {!! minimizeButton('pro_collapse_employee') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_employee">
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
                                <!-- alias -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('alias')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('alias')) title="{!!$errors->first('alias')!!}" @endif>
                                        {!! Form::label('alias','Alias:') !!}
                                        {!! Form::text('alias', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('alias'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="aliasStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="aliasStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- date_of_birth -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('date_of_birth')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('date_of_birth')) title="{!!$errors->first('date_of_birth')!!}" @endif>
                                        {!! Form::label('date_of_birth','Date of birth:') !!}
                                        {{--{!! Form::date('date_of_birth', null, ['class'=>'form-control']) !!}--}}
                                        <input type="text" class="form-control" name="date_of_birth" id="date_of_birth"
                                               value="{{str_replace('/','-',dateBS(date('Y-m-d')))}}">
                                        @if($errors->has('date_of_birth'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="date_of_birthStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="date_of_birthStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- marital_status -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('marital_status')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('marital_status')) title="{!!$errors->first('marital_status')!!}" @endif>
                                        {!! Form::label('marital_status','Marital Status:') !!}
                                        {!! Form::select('marital_status', ['0' => 'Single', '1' => 'Married'], null, ['class'=>'form-control']) !!}
                                        @if($errors->has('marital_status'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="marital_statusStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="marital_statusStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- employment_type -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('employment_type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('employment_type','Employment Type:') !!}
                                        {!! Form::select('employment_type', ['0' =>'Temporary', '1' => 'Permanent'], null, ['class'=>'form-control']) !!}
                                        @if($errors->has('employment_type'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="employment_typeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('employment_type') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="employment_typeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>


                                <!-- designation_id -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('designation_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('designation_id','Designation:') !!}
                                        {!! Form::select('designation_id', $designations,null, ['class'=>'form-control']) !!}
                                        @if($errors->has('designation_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="designation_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('designation_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="designation_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- date_of_join -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('date_of_join')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('date_of_join')) title="{!!$errors->first('date_of_join')!!}" @endif>
                                        {!! Form::label('date_of_join','Date of join:') !!}
                                        {{--{!! Form::text('date_of_join', null, ['class'=>'form-control']) !!}--}}
                                        <input type="text" class="form-control" name="date_of_join" id="date_of_join"
                                               value="{{str_replace('/','-',dateBS(date('Y-m-d')))}}">
                                        @if($errors->has('date_of_join'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="date_of_joinStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="date_of_joinStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- phone -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('phone')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('phone')) title="{!!$errors->first('phone')!!}" @endif>
                                        {!! Form::label('phone','Phone:') !!}
                                        {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('phone'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="phoneStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="phoneStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- mobile -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('mobile')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('mobile')) title="{!!$errors->first('mobile')!!}" @endif>
                                        {!! Form::label('mobile','Mobile:') !!}
                                        {!! Form::text('mobile', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('mobile'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="mobileStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="mobileStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- pan_no -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('pan_no')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('pan_no','PAN No:') !!}
                                        {!! Form::text('pan_no', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('pan_no'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="pan_noStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('pan_no') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="pan_noStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- district -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('district')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('district')) title="{!!$errors->first('district')!!}" @endif>
                                        {!! Form::label('district','District:') !!}
                                        {!! Form::select('district', $districts, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('district'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="districtStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="districtStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- address -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('address')) title="{!!$errors->first('address')!!}" @endif>
                                        {!! Form::label('address','Address:') !!}
                                        {!! Form::text('address', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('address'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="addressStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="addressStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight"
                         data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Image</h3>
                                {!! minimizeButton('pro_collapse_member_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_member_image">
                                <!-- image -->
                                <label class="drop-zone form-group col-md-12 col-lg-12 @if($errors->has('image')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" for="image_browse">
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
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Publish</h3>
                                {!! minimizeButton('pro_collapse_employee_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_employee_save">
                                <!-- status -->
                                <div class="form-group col-md-3 col-lg-3 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color"
                                           checked="checked"/>
                                    <label for="status">Is Active?</label>
                                </div>
                                <!-- providend_fund -->
                                <div class="form-group col-md-4 col-lg-4 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="pf" id="pf" class="second_color"/>
                                    <label for="pf">Providend Fund</label>
                                </div>
                                <!-- cit -->
                                <div class="form-group col-md-5 col-lg-5 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="cit" id="cit" class="second_color"/>
                                    <label for="cit">Citizen Investment Trust</label>
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
            $('#date_of_join').nepaliDatePiDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
        });
    </script>
@stop