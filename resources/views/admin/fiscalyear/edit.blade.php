@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('fiscalyear.index')}}"><i class="fa fa-buysellads"></i> Fiscalyear</a></li>
                    <li class="active"><a href="{{route('fiscalyear.edit', $fiscalyear->slug)}}"><i class="fa fa-edit"></i> Edit Fiscalyear</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('fiscalyear.index')}}" class="btn btn-info showToolTip" title="Edit Fiscalyear" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Fiscalyears</span></a>
                        <a href="{{route('fiscalyear.create')}}" class="btn btn-warning showToolTip" title="Add Fiscalyear" role="button" data-placement="top"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs hidden-sm">Add Fiscalyear</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($fiscalyear, ['route'=>['fiscalyear.update', $fiscalyear->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Fiscalyear</h3>
                                {!! minimizeButton('pro_collapse_fiscalyear') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_fiscalyear">

                                <!-- fy -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('fy')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('fy','Fiscal Year:') !!}
                                        <span class="glyphicon glyphicon-info-sign showInfo faa-bounce animated-hover" title="Notice" data-content="कृपया Fiscal Year चढाउदा एउटै ढाँचामा हाल्नुहाेला ।  eg. 2072-073 or 2072/073 मध्येकाे कुनै एउटा मात्र प्रयाेग  गर्नुहाेला ।" data-trigger="focus" data-placement="top" data-toggle="popover" tabindex="0" role="button"></span>
                                        {!! Form::text('fy', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('fy'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="fyStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('fy') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="fyStatus" class="sr-only">(success)</span>
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
                                {!! minimizeButton('pro_collapse_fiscalyear_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_fiscalyear_image">
                                <!-- image -->
                                <label class="drop-fiscalyear form-group col-md-12 col-lg-12 @if($errors->has('image')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback" for="image_browse">
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
                                {!! minimizeButton('pro_collapse_fiscalyear_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_fiscalyear_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" @if($fiscalyear->status == 1 ) checked="checked" @endif />
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
    <script type="text/javascript">
        $('document').ready(function() {

        });
    </script>
@stop