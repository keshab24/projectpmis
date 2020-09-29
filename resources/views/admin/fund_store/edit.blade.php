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
                    <li><a href="{{route('fundstore.index')}}"><i class="fa fa-buysellads"></i> Fund Store</a>
                    </li>
                    <li class="active"><a href="{{route('fundstore.create')}}"><i class="fa fa-plus"></i>
                            Add Fund Store</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('fundstore.index')}}" class="btn btn-info showToolTip"
                           title="Edit Fund Store" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Fund Stores</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($fundStore, ['route'=>['fundstore.update', $fundStore->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add
                                    Fund Store</h3>
                                {!! minimizeButton('pro_collapse_fundStore') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_fundStore">
                                <div class="clearfix"></div>
                                <!-- name_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('name_eng')) title="{!!$errors->first('name_eng')!!}" @endif>
                                        {!! Form::label('name_eng','Name in English:') !!}
                                        {!! Form::text('name_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('name_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('name')) title="{!!$errors->first('name')!!}" @endif>
                                        {!! Form::label('name','Name in Nepali:') !!}
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
                                <!-- description_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('description_eng')) title="{!!$errors->first('description_eng')!!}" @endif>
                                        {!! Form::label('description_eng','Description in English:') !!}
                                        {!! Form::textarea('description_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('description_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="description_engStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="description_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                               <!-- description -->
                               <div class="form-group col-md-6 col-lg-6 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                   <div class="input-group pro_make_full showToolTip"
                                        @if($errors->has('description')) title="{!!$errors->first('description')!!}" @endif>
                                       {!! Form::label('description','Description in Nepali:') !!}
                                       {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
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
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight"
                         data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Account Details</h3>
                                {!! minimizeButton('pro_collapse_account_details') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_account_details">
                                <!-- account_name -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('account_name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('account_name')) title="{!!$errors->first('account_name')!!}" @endif>
                                        {!! Form::label('account_name','Account Name:') !!}
                                        {!! Form::text('account_name', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('account_name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="account_nameStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="account_nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- account_no -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('account_no')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('account_no')) title="{!!$errors->first('account_no')!!}" @endif>
                                        {!! Form::label('account_no','Account Number:') !!}
                                        {!! Form::text('account_no', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('account_no'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="account_noStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="account_noStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- branch -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('branch')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('branch')) title="{!!$errors->first('branch')!!}" @endif>
                                        {!! Form::label('branch','Branch:') !!}
                                        {!! Form::text('branch', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('branch'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="branchStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="branchStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- address -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
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

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Publish</h3>
                                {!! minimizeButton('pro_collapse_fundStore_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_fundStore_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color"
                                           checked="checked"/>
                                    <label for="status">Is Active?</label>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                    Save
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

        });
    </script>
@stop