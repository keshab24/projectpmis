@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{ asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('engineers.index')}}"><i class="fa fa-buysellads"></i> Human Resource</a></li>
                    <li class="active"><a href="{{route('engineers.create')}}"><i class="fa fa-plus"></i> Add Human Resource</a></li>
                    <li><a href="{{ route('user.edit',$engineer->myUser->slug)}}"><i class="fa fa-eye"></i> User</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('engineers.index')}}" class="btn btn-info showToolTip" title="Edit Engineer" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Human Resource</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($engineer, ['route'=>['engineers.update', $engineer->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Edit Human Resource</h3>
                                {!! minimizeButton('pro_collapse_engineer') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_engineer">
                                <!-- type -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('type','Designation') !!}
                                        {!! Form::select('type', $designations, null, ['class'=>'form-control']) !!}
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
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','Name:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control','required']) !!}
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

                                <!-- home_address -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('home_address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('home_address','Address:') !!}
                                        {!! Form::text('home_address', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('home_address'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="home_addressStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('home_address') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="home_addressStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- email -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('email')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('email','Email:') !!}
                                        {!! Form::email('email', null, ['class'=>'form-control','required']) !!}
                                        @if($errors->has('email'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="emailStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('email') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="emailStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- mobile -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('mobile')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('mobile','Mobile Number:') !!}
                                        {!! Form::text('mobile', null, ['class'=>'form-control','required']) !!}
                                        @if($errors->has('mobile'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="mobileStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('mobile') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="mobileStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- phone -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('phone')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('phone','Phone Number:') !!}
                                        {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('phone'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="phoneStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('phone') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="phoneStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Implementing Office -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('Implementing Office')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('Implementing Office','Implementing Office') !!}
                                        {!! Form::select('implementing_office', $implementing_office ,null, ['class'=>'form-control']) !!}
                                        @if($errors->has('Implementing Office'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="Implementing OfficeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('Implementing Office') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="Implementing OfficeStatus" class="sr-only">(success)</span>
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
                                {!! minimizeButton('pro_collapse_member_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_member_image">
                                <div class="pro_image_preview">
                                    @if($engineer->image !='' && $engineer->image != NULL && file_exists('public/images/engineer/thumbnail/thumbvtx'.$engineer->image))
                                    <img src="{{asset('public/images/engineer/thumbnail/thumbvtx'.$engineer->image)}}" alt="{{$engineer->image}}" title="{{$engineer->name}}" class="img-thumbnail showToolTip" />
                                    <a href="{{route('download_file',['engineer',$engineer->image])}}" class="btn btn-xs btn-warning download_image showToolTip" type="button" role="button" title="Download File"><span class="glyphicon glyphicon-save"></span> Download</a>
                                    <a href="{{route('delete_file',['engineer',$engineer->slug,$engineer->image])}}" class="btn btn-xs btn-danger showToolTip" type="button" role="button" title="Delete This Image"><span class="glyphicon glyphicon-trash"></span> Delete</a>
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
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_engineer_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_engineer_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" @if($engineer->status==1)checked="checked" @endif />
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
            $('document').ready(function() {
                var $source = [@foreach($es as $e) " {!! $e->name!!} ", @endforeach];
                var $name = $('#name');
                $name.tokenfield({
                    autocomplete: {
                        source: $source,
                        delay: 100
                    },
                    createTokensOnBlur:true,
                    showAutocompleteOnFocus: true,
                    delimiter: "%%%",
                    limit:1
                });
                preventDuplicate($name);
            });
        });
    </script>
@stop