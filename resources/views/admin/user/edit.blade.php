@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('user.index')}}"><i class="fa fa-buysellads"></i> Admin</a></li>
                    <li class="active"><a href="{{route('user.edit',$user_edit->slug)}}"><i class="fa fa-edit"></i> Edit Admin</a></li>

                @if($user_edit->relatedLink())
{{--                        {!! dd('here') !!}--}}
                        <li><a href="{{$user_edit->relatedLink()['route']}}"><i class="fa fa-eye"></i> {{ $user_edit->relatedLink()['related'] }}</a></li>
                    @endif
{{--                    {!! dd('here') !!}--}}
                </ol>

            @if(Auth::user()->access == 'Root Level')
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        <div class="col-lg-6 col-md-6">
                            <a href="{{route('user.create')}}" class="btn btn-warning showToolTip" title="Add Admin" role="button" data-placement="top"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs hidden-sm">Add Admin</span></a>
                            <a href="{{route('user.index')}}" class="btn btn-info showToolTip" title="Edit Admins" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Admins</span></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                @endif
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($user_edit,['route'=>['user.update',$user_edit->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-edit"></span> Edit Admin <span></span></h3>
                                {!! minimizeButton('pro_collapse_user') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user">

                            <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
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
                            <!-- email -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('email')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('email')) title="{!!$errors->first('email')!!}" @endif>
                                        {!! Form::label('email','Email:') !!}
                                        {!! Form::text('email', null, ['class'=>'form-control pro_token_field']) !!}
                                    @if($errors->has('email'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="emailStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="emailStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                            <!-- phone -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('phone')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('phone')) title="{!!$errors->first('phone')!!}" @endif>
                                        {!! Form::label('phone','Phone:') !!}
                                        {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                                    @if($errors->has('phone'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="phoneStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="phoneStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            <!-- access -->
                                <div @if(Auth::user()->access != 'Root Level' && Auth::user()->access != 'Top Level') style="display:none";  @endif class="form-group col-md-6 col-lg-6  @if($errors->has('access')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('access')) title="{!!$errors->first('access')!!}" @endif>
                                        {!! Form::label('access','Access Level:') !!}
                                        {!! Form::select('access', ['Top Level'=>'Top Level','Limited'=>'Limited'], NULL, ['class'=>'form-control']) !!}
                                        @if($errors->has('access'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="accessStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="accessStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                @if(!$user_edit->Contractor)
                                    @if(Auth::User()->implementingOffice->isMonitoring==0 || Auth::User()->id==4)
                                        @if($user_edit->id!=Auth::User()->id)
                                        <!-- type_flag -->
                                            <?php $type=array(8=>'Accountant',9=>'Administration') ?> @if(Auth::User()->id==4) <?php $type[7]='External'; ?>  @endif
                                            <div class="form-group col-md-6 col-lg-6 @if($errors->has('type_flag')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                                <div class="input-group pro_make_full showToolTip">
                                                    {!! Form::label('type_flag','Type') !!}
                                                    {!! Form::select('type_flag', $type ,null, ['class'=>'form-control']) !!}
                                                    @if($errors->has('type_flag'))
                                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                        <span id="type_flagStatus" class="sr-only">(error)</span>
                                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('type_flag') !!}</small></div>
                                                    @elseif(count($errors->all())>0)
                                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                        <span id="type_flagStatus" class="sr-only">(success)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <div @if(Auth::User()->implementingOffice->isMonitoring==0) style="display:none" @endif class="io_holder">
                                        <!-- implementing_office_id -->
                                        <div class="form-group col-md-6 col-lg-6 @if($errors->has('implementing_office_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" >
                                            <div class="input-group pro_make_full showToolTip">
                                                {!! Form::label('implementing_office_id','Implementing Office:') !!}
                                                {!! Form::select('implementing_office_id', $implementing_offices,null, ['class'=>'form-control']) !!}
                                                @if($errors->has('implementing_office_id'))
                                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                          aria-hidden="true"></span>
                                                    <span id="implementing_office_idStatus" class="sr-only">(error)</span>
                                                    <div class="alert alert-danger">
                                                        <small>
                                                            <i class="fa fa-warning"></i> {!! $errors->first('implementing_office_id') !!}
                                                        </small>
                                                    </div>
                                                @elseif(count($errors->all())>0)
                                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                          aria-hidden="true"></span>
                                                    <span id="implementing_office_idStatus" class="sr-only">(success)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($user_edit->type_flag == 7)
                                    <div class="mo_holder">
                                        <!-- monitoring_offices -->
                                        <div class="form-group col-md-6 col-lg-6 @if($errors->has('monitoring_offices')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                            <div class="input-group pro_make_full showToolTip">
                                                {!! Form::label('monitoring_offices','Monitoring Offices') !!}
                                                {!! Form::text('monitoring_offices', implode(',',$user_edit->externalUserMonitoring()->pluck('name')->toArray()), ['class'=>'form-control']) !!}
                                                @if($errors->has('monitoring_offices'))
                                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="monitoring_officesStatus" class="sr-only">(error)</span>
                                                    <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('monitoring_offices') !!}</small></div>
                                                @elseif(count($errors->all())>0)
                                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                    <span id="monitoring_officesStatus" class="sr-only">(success)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('type_flag')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" >
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('type_flag','User Type:') !!}
                                        {!! Form::select('type_flag', $user_types,null, ['class'=>'form-control']) !!}
                                        @if($errors->has('type_flag'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="implementing_office_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('type_flag') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="implementing_office_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>



                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('description')) title="{!!$errors->first('description')!!}" @endif>
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>7]) !!}
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
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Change Password</h3>
                                {!! minimizeButton('pro_collapse_user_password') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user_password">
                                <!-- password -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('password')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('password')) title="{!!$errors->first('password')!!}" @endif>
                                        {!! Form::label('password','Password:') !!}
                                        {!! Form::password('password', ['class'=>'form-control']) !!}
                                        @if($errors->has('password'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="passwordStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="passwordStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- password_again -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('password_again')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('password_again')) title="{!!$errors->first('password_again')!!}" @endif>
                                        {!! Form::label('password_again','Password Again:') !!}
                                        {!! Form::password('password_again', ['class'=>'form-control']) !!}
                                        @if($errors->has('password_again'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="password_againStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="password_againStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Image</h3>
                                {!! minimizeButton('pro_collapse_user_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user_image">
                                <div class="pro_image_preview">
                                    @if($user_edit->image !='' && $user_edit->image != NULL && file_exists('public/images/users/thumbnail/thumbvtx'.$user_edit->image))
                                        <img src="{{asset('public/images/users/thumbnail/thumbvtx'.$user_edit->image)}}" alt="{{$user_edit->image}}" title="{{$user_edit->name}}" class="img-thumbnail showToolTip" />
                                        <a href="{{route('download_file',['users',$user_edit->image])}}" class="btn btn-xs btn-warning download_image showToolTip" type="button" role="button" title="Download File"><span class="glyphicon glyphicon-save"></span> Download</a>
                                        <a href="{{route('delete_file',['users',$user_edit->slug,$user_edit->image])}}" class="btn btn-xs btn-danger showToolTip" type="button" role="button" title="Delete This Image"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                    @else
                                        <img src="{{asset('public/images/no_image.jpg')}}" alt="No Image" title="No Image Available!!" class="img-thumbnail showToolTip" />
                                    @endif
                                </div>
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
                                <h3 class="panel-title pull-left"><span class="fa fa-map-marker"></span> Select District</h3>
                                {!! minimizeButton('pro_collapse_user_district') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user_district">
                                <!-- District -->
                                <div class="form-group @if($errors->has('district_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" >
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('district_id','District:') !!}
                                        {!! Form::select('district_id', $districts,null, ['class'=>'form-control']) !!}
                                        @if($errors->has('district_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="implementing_office_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('district_id') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="implementing_office_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_user_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" @if($user_edit->status == 1) @else checked="checked" @endif />
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
    <script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $('select').select2();
        });

            var $source = [<?php foreach($monitoring_offices as $monitoring_office) echo '"'.$monitoring_office->name.'",'; ?>];
            var $monitoring_offices = $('#monitoring_offices');
            $monitoring_offices.tokenfield({
                autocomplete: {
                    source: $source,
                    delay: 100
                },
                createTokensOnBlur:true,
                showAutocompleteOnFocus: true
            });

            preventDuplicate($monitoring_offices);
            $('#type_flag').change(function (){
                if(this.value==7){
                    $('.io_holder').hide();
                    $('.mo_holder').show();
                }else{
                    $('.io_holder').show();
                    $('.mo_holder').hide();
                }
            });
    </script>
@stop