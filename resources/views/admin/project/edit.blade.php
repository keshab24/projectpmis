@extends('layouts.admin_layout')
@section('headerContent')
    <link href="{{ asset('public/admin/css/pro_map.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet"
          href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}"
          type="text/css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
                    <li><a href="{{route('project.index')}}"><i class="fa fa-buysellads"></i> Project</a></li>
                    <li class="active"><a href="{{route('project.edit', $project->id)}}"><i
                                    class="fa fa-edit"></i> Edit Project</a></li>
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('project.index')}}/search?search={{$setting->project_code}}"
                           class="btn btn-warning showToolTip"
                           title="Back To Index" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-chevron-left"></span><span class="hidden-xs hidden-sm"> Back</span></a>
                        <a href="{{route('project.index')}}" class="btn btn-info showToolTip"
                           title="Edit Project" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Projects</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($project, ['route'=>['project.update', $project->id],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div {{ restrictToImplementingOffice() }}>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span>
                                        Edit
                                        Project</h3>
                                    {!! minimizeButton('pro_collapse_project') !!}
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_project">
                                    <!-- budget_topic_id -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('budget_topic_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('budget_topic_id','ब.उ.शी.नं.:') !!}
                                            {!! Form::select('budget_topic_id', $budgettopics, $setting->budget_topic->id, ['class'=>'form-control']) !!}
                                            @if($errors->has('budget_topic_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="budget_topic_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('budget_topic_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="budget_topic_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- expenditure_topic_id -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('expenditure_topic_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('expenditure_topic_id','ख.शी.नं.:') !!}
                                            {!! Form::select('expenditure_topic_id', $expendituretopics, Input::old('expenditure_topic_id') ?: $setting->expenditure_topic->id, ['class'=>'form-control']) !!}
                                            @if($errors->has('expenditure_topic_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="expenditure_topic_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('expenditure_topic_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="expenditure_topic_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- district_id -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('district_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('district_id','कार्यक्रम संचालन जिल्ला:') !!}
                                            {!! Form::select('district_id', $districts, null, ['class'=>'form-control']) !!}
                                            @if($errors->has('district_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="district_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('district_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="district_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- project_code -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('project_code')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip"
                                             @if($errors->has('project_code')) title="{!!$errors->first('project_code')!!}" @endif>
                                            <button class="btn btn-default btn-xs pull-right showToolTip" title="Update Project Code" id="update_project_code" type="button"><i class="fa fa-refresh"></i></button>
                                            {!! Form::label('project_code','Project code') !!}
                                            {!! Form::text('project_code', $setting->project_code, ['readonly'=>'readonly','class'=>'form-control','required'=>'required']) !!}
                                            @if($errors->has('project_code'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="project_codeStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="project_codeStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- name -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('name','कार्यक्रम/आयोजनाको नाम:') !!}
                                            {!! Form::text('name', null, ['class'=>'form-control focus_field','id'=>'name']) !!}
                                            @if($errors->has('name'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="nameStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small><i class="fa fa-warning"></i> {!! $errors->first('name') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="nameStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- name_eng -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('name_eng','Project Name:') !!}
                                            {!! Form::text('name_eng', null, ['class'=>'form-control','id'=>'name_eng']) !!}
                                            @if($errors->has('name_eng'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="name_engStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('name_eng') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="name_engStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- construction_type_id -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('construction_type_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('construction_type_id','कार्यक्रम शिर्षक:') !!}
                                            {!! Form::select('construction_type_id', $constructiontypes, null, ['class'=>'form-control']) !!}
                                            @if($errors->has('construction_type_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="construction_type_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('construction_type_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="construction_type_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- address -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('address','ठेगाना:') !!}
                                            {!! Form::text('address', null, ['class'=>'form-control']) !!}
                                            @if($errors->has('address'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="addressStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('address') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="addressStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- description -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('description','नोट:') !!}
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
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><span
                                                class="glyphicon glyphicon-map-marker"></span> Coordinates</h3>
                                    {!! minimizeButton('pro_collapse_port_map') !!}
                                </div>
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('manual_coordinates')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('manual_coordinates')) title="{!!$errors->first('manual_coordinates')!!}" @endif>
                                        {!! Form::label('manual_coordinates','Manual Coordinates') !!}
                                        {!! Form::text('manual_coordinates', $project->coordinates, ['class'=>'form-control']) !!}
                                        @if($errors->has('manual_coordinates'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="manual_coordinatesStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="manual_coordinatesStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_port_map">
                                    <!-- for coordinates -->
                                    <div class="form-group input-group @if(count($errors->all())>0){{($errors->has('coordinates'))?'has-error':'has-success'}}@endif col-lg-12 col-md-12">
                                        <input type='hidden' size='38' maxlength='40' name='coordinates'
                                               id='coordinates' class="form-control"
                                               value="{{ $project->coordinates }}"/>
                                        @if($errors->has('coordinates'))
                                            <span class="glyphicon glyphicon-exclamation-sign form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <p class="help-block">{{$errors->first('coordinates')}}</p>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok form-control-feedback"
                                                  aria-hidden="true"></span>
                                        @endif
                                    </div>
                                    <input id="pac-input" class="controls" type="text" placeholder="Search Place">
                                    <div id='map' class='mapCooL' style="width:100%; height:370px;"></div>
                                    <!-- for coordinates ends -->
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Other Details</h3>
                                {!! minimizeButton('pro_collapse_project_other') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_project_other">
                                <!-- headquarter -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('headquarter')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('headquarter','सदरमुकाममा रहेको:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('headquarter', insideHeadquarter(), $project->headquarter, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('headquarter'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="headquarterStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('headquarter') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="headquarterStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- bstype  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('bstype')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Bs Type','Structure Type:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('bstype', bsType(), $project->bstype, ['id'=>'bstype','class'=>'form-control']) !!}</div>
                                        @if($errors->has('bstype'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="bstypeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('bstype') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="bstypeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- story_area_unite -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('story_area_unite')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('story_area_unite','भवनको तल्ला:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('story_area_unite', getStoreyArea(), $project->story_area_unite, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('story_area_unite'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="story_area_uniteStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('story_area_unite') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="story_area_uniteStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- pr_code -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('pr_code','भवनको क्षेत्रफल:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('pr_code', null, ['class'=>'form-control focus_field']) !!}</div>
                                        @if($errors->has('pr_code'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="pr_codeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('pr_code') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="pr_codeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            {{--<!-- land_ownership  -->--}}
                            {{--<div class="form-group col-md-6 col-lg-6 @if($errors->has('land_ownership')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">--}}
                            {{--<div class="input-group pro_make_full showToolTip">--}}
                            {{--<div class="col-md-4">{!! Form::label('Land Ownership','लाल पुर्जा:') !!}</div>--}}
                            {{--<div class="col-md-8">{!! Form::select('land_ownership', array('0'=>'थाहा नभएको','1'=>'नभएको','2'=>'भएको'), null, ['id'=>'land_ownership','class'=>'form-control land_ownership']) !!}</div>--}}
                            {{--@if($errors->has('land_ownership'))--}}
                            {{--<span class="glyphicon glyphicon-remove-circle form-control-feedback"--}}
                            {{--aria-hidden="true"></span>--}}
                            {{--<span id="land_ownershipStatus" class="sr-only">(error)</span>--}}
                            {{--<div class="alert alert-danger">--}}
                            {{--<small>--}}
                            {{--<i class="fa fa-warning"></i> {!! $errors->first('land_ownership') !!}--}}
                            {{--</small>--}}
                            {{--</div>--}}
                            {{--@elseif(count($errors->all())>0)--}}
                            {{--<span class="glyphicon glyphicon-ok-circle form-control-feedback"--}}
                            {{--aria-hidden="true"></span>--}}
                            {{--<span id="land_ownershipStatus" class="sr-only">(success)</span>--}}
                            {{--@endif--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <!-- swamittwo  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('swamittwo')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Swamittwi','जग्गाको स्वमित्त्व:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('swamittwo', swamittwo(), null, ['class'=>'form-control swamittwo']) !!}</div>
                                        @if($errors->has('swamittwo'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="swamittwoStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('swamittwo') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="swamittwoStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- kittanumber  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('kittanumber')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Kitta Number','कित्ता नम्बर:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('kittanumber', null, ['class'=>'form-control','id'=>'kittanumber']) !!}</div>
                                        @if($errors->has('kittanumber'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="kittanumberStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('kittanumber') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="kittanumberStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- swamittwo  -->
                                <div class="form-group col-md-6 col-lg-6  @if($errors->has('whose')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Whose','कस्को स्वमित्त्व:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('whose', jaggaType(), null, ['class'=>'form-control','id'=>'whose']) !!}</div>
                                        @if($errors->has('whose'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="whoseStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('whose') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="whoseStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- shitnumber  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('shitnumber')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Sheet Number','शीट नम्बर:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('shitnumber', null, ['class'=>'form-control','id'=>'shitnumber']) !!}</div>
                                        @if($errors->has('shitnumber'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="shitnumberStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('shitnumber') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="shitnumberStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- soiltest  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('soiltest')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Soil Test','माटो जाच:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('soiltest', soilTest(), null, ['id'=>'soiltest','class'=>'form-control']) !!}</div>
                                        @if($errors->has('soiltest'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="soiltestStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('soiltest') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="soiltestStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- baringcapacity  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('baringcapacity')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Bearing Capacity','Bearing Capacity:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('baringcapacity', null, ['id'=>'baringcapacity','class'=>'form-control']) !!}</div>
                                        @if($errors->has('baringcapacity'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="baringcapacityStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('baringcapacity') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="baringcapacityStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- rooftype  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('rooftype')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Roof Type','छानाको प्रकृति:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('rooftype', rooftype(),$project->rooftype, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('rooftype'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="rooftypeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('rooftype') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="rooftypeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- doorwindow  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('doorwindow')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Door Windor','झ्याल ढोका:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('doorwindow', doorWindow(),$project->doorWindor, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('doorwindow'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="doorwindowStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('doorwindow') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="doorwindowStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- doorwindow  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('wall_type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Wall Type','गारोको किसिम :') !!}</div>
                                        <div class="col-md-8">{!! Form::select('wall_type', wallType(),$project->doorWindor, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('wall_type'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="wall_typeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('wall_type') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="wall_typeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight"
                         data-appear-delay="1200">
                        <div {{ restrictToImplementingOffice() }}>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span>
                                        Choose Implement Office</h3>
                                    {!! minimizeButton('pro_collapse_tree') !!}
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_tree">
                                    <!-- implementing_office_id -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('implementing_office_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback"
                                         @if($user_info->access!='Root Level') @if($user_info->implementingOffice->isMonitoring==0 ) style="display:none" @endif @endif>
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('implementing_office_id','Implementing Office:') !!}
                                            {{--replaced the collective form because of the need of opt group --}}
                                            {{--implementing office ko child office pani chahiyeko le garda --}}
                                            @if($user_info->implementingOffice->isNewTown)
                                                {!! Form::select('implementing_office_id', $implementing_offices,$project->implementing_office_id, ['class'=>'form-control']) !!}
                                            @else
                                                {!! piuOfficesSelectList($implementing_offices_new_update, $setting->implementing_office->id,'implementing_office_id') !!}
                                            @endif
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
                                    <!-- monitoring_office_id -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('monitoring_office_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback"
                                         @if($user_info->access!='Root Level') @if($user_info->implementingOffice->isMonitoring==1 ) style="display:none" @endif @endif>
                                        <div class="input-group pro_make_full showToolTip"
                                             @if($errors->has('monitoring_office_id')) title="{!!$errors->first('monitoring_office_id')!!}" @endif>
                                            {!! Form::label('monitoring_office_id','Monitoring office') !!}
                                            {!! Form::select('monitoring_office_id', $monitoring_offices,$setting->monitoringOffice->id, ['class'=>'form-control']) !!}
                                            @if($errors->has('monitoring_office_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="monitoring_office_idStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="monitoring_office_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <br/>
                                    {!! Form::hidden('monitoring_office_id',$setting->monitoringOffice->id,['id'=>'monitoring_office_id']) !!}
                                    {!! Form::hidden('level','1',['id'=>'level']) !!}
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span>
                                        Manage Group Detail </h3>
                                    {!! minimizeButton('pro_collapse_manage_group') !!}
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_manage_group">
                                    <!-- project_group_id -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('project_group_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('project_group_id','Sub Group:') !!}
                                            {!! Form::select('project_group_id', $project_groups,$project->project_group_id,['class'=>'form-control']) !!}
                                            @if($errors->has('project_group_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="project_group_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('project_group_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="project_group_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- nature_of_project_id -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('nature_of_project_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('nature_of_project_id','Nature of Project (Contruction type):') !!}
                                            {!! Form::select('nature_of_project_id', nature_of_project()['eng'][$project->monitoring_office_id], $project->nature_of_project_id, ['class'=>'form-control']) !!}
                                            @if($errors->has('nature_of_project_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="nature_of_project_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('nature_of_project_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="nature_of_project_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- construction_located_area_id -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('construction_located_area_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('construction_located_area_id','Construction Located Area:') !!}
                                            {!! Form::select('construction_located_area_id', $construction_located_area,$project->construction_located_area_id, ['class'=>'form-control']) !!}
                                            @if($errors->has('construction_located_area_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="construction_located_area_idStatus"
                                                      class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('construction_located_area_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="construction_located_area_idStatus"
                                                      class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><i class="fa fa-share"></i> Unit</h3>
                                    {!! minimizeButton('pro_collapse_unit_save') !!}
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_unit_save">
                                    <!-- unit -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('unit')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip"
                                             @if($errors->has('unit')) title="{!!$errors->first('unit')!!}" @endif>
                                            {!! Form::label('unit','Unit:') !!}
                                            {!! Form::select('unit', unit(), null, ['class'=>'form-control']) !!}
                                            @if($errors->has('unit'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="unitStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="unitStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- quantity -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('quantity')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip"
                                             @if($errors->has('quantity')) title="{!!$errors->first('quantity')!!}" @endif>
                                            {!! Form::label('quantity','परिमाण:') !!}
                                            {!! Form::text('quantity', 100, ['class'=>'form-control']) !!}
                                            @if($errors->has('quantity'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="quantityStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="quantityStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><i class="fa fa-money"></i> Budget Info</h3>
                                    {!! minimizeButton('pro_collapse_budget_info_save') !!}
                                </div>

                                <div class="panel-body collapse in" id="pro_collapse_budget_info_save">
                                    <!-- estimated_amount -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('estimated_amount')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('estimated_amount','Initial Cost Estimate') !!}
                                            <small>(रु हजारमा)</small>
                                            :
                                            {!! Form::text('estimated_amount', $project->procurement?$project->procurement->estimated_amount:"", ['class'=>'form-control']) !!}
                                            @if($errors->has('estimated_amount'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="estimated_amountStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('estimated_amount') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="estimated_amountStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- design_est_swikrit_miti -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('design_est_swikrit_miti')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('design_est_swikrit_miti','Design Approved Date:') !!}
                                            {!! Form::text('design_est_swikrit_miti', $project->procurement?$project->procurement->design_est_swikrit_miti:"", ['class'=>'form-control nepaliDate']) !!}
                                            @if($errors->has('design_est_swikrit_miti'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="design_est_swikrit_mitiStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('design_est_swikrit_miti') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="design_est_swikrit_mitiStatus"
                                                      class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- con_est_amt_net -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('con_est_amt_net')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('con_est_amt_net','Approved Cost Estimate:') !!}
                                            {!! Form::text('con_est_amt_net', $project->procurement?$project->procurement->con_est_amt_net:"", ['class'=>'form-control']) !!}
                                            @if($errors->has('con_est_amt_net'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="con_est_amt_netStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('con_est_amt_net') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="con_est_amt_netStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- est_approved_date -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('est_approved_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('est_approved_date','Cost Est.. Approved Date:') !!}
                                            {!! Form::text('est_approved_date', $project->procurement?$project->procurement->est_approved_date:"", ['class'=>'form-control nepaliDate']) !!}
                                            @if($errors->has('est_approved_date'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="est_approved_dateStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('est_approved_date') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="est_approved_dateStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- contract_amount -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('contract_amount')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('contract_amount','Contract Amount:') !!}
                                            {!! Form::text('contract_amount', $project->procurement?$project->procurement->contract_amount:"", ['class'=>'form-control']) !!}
                                            @if($errors->has('contract_amount'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="contract_amountStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('contract_amount') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="contract_amountStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- contract_date -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('contract_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('contract_date','Contract Date:') !!}
                                            <input class="form-control" id="contract_date" type="text"
                                                   name="contract_date"
                                                   value="{{$project->procurement?$project->procurement->contract_date:""}}"/>
                                            @if($errors->has('contract_date'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="contract_dateStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('contract_date') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="contract_dateStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- completion_date -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('completion_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('completion_date','Contract Ending Date:') !!}
                                            <input class="form-control nepaliDate" id="completion_date" type="text"
                                                   name="completion_date"
                                                   value="{{$project->procurement?$project->procurement->completion_date:""}}"/>
                                            @if($errors->has('completion_date'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="completion_dateStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('completion_date') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="completion_dateStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- contingency -->
                                    <div class="form-group col-md-6 col-lg-6 @if($errors->has('contingency')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('contingency','Contingency(%):') !!}
                                            {!! Form::select('contingency', contingencyPercentages(),$project->procurement?$project->procurement->contingency:"",['class'=>'form-control']) !!}
                                            @if($errors->has('contingency'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="contingencyStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('contingency') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="contingencyStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><i class="fa fa-calendar"></i> Creation Info</h3>
                                    {!! minimizeButton('pro_collapse_creation_info_save') !!}
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_creation_info_save">
                                    <!-- approved_date -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('approved_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip"
                                             @if($errors->has('approved_date')) title="{!!$errors->first('approved_date')!!}" @endif>
                                            {!! Form::label('approved_date','कार्यक्रम पारित मिति:') !!}
                                            <input class="form-control nepaliDate" id="approved_date" type="text"
                                                   name="approved_date"
                                                   value="{{ $project->approved_date }}"/>
                                            @if($errors->has('approved_date'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="approved_dateStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="approved_dateStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><i class="fa fa-building"></i> Design Type</h3>
                                    {!! minimizeButton('pro_collapse_creation_design_type') !!}
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_creation_design_type">
                                    <!-- design_type -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('design_type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip"
                                             @if($errors->has('design_type')) title="{!!$errors->first('design_type')!!}" @endif>
                                            {!! Form::label('design_type','Design Type') !!}
                                            {!! Form::select('design_type', designType(),null,['class'=>'form-control']) !!}
                                            @if($errors->has('design_type'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="design_typeStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="design_typeStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($project->revisedFrom)
                            <div class="panel panel-default">
                                <div class="panel-heading ">
                                    <h3 class="panel-title pull-left"> Continuation of Cancelled Project</h3>
                                    {!! minimizeButton('pro_collapse_cancelled_project') !!}
                                </div>
                                <div class="panel-body collapse show" id="pro_collapse_cancelled_project">
                                    <h3>
                                        <a href="{{route('project.show', $project->cancelled_project_id)}}"
                                           title="View Detail of Cancelled/Terminated Project" target="_blank">
                                            {{ $project->revisedFrom->name }} ({{ $project->revisedFrom->project_code }}
                                            )
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        @endif
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Publish</h3>
                                {!! minimizeButton('pro_collapse_project_save') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_project_save">
                                <div {{ restrictToImplementingOffice() }}>
                                    <!-- start_fy_id -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('start_fy_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('start_fy_id','Starting Fiscal Year:') !!}
                                            {!! Form::select('start_fy_id', $fiscal_years,null, ['class'=>'form-control']) !!}
                                            @if($errors->has('start_fy_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="start_fy_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small>
                                                        <i class="fa fa-warning"></i> {!! $errors->first('start_fy_id') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="start_fy_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- status -->
                                    <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                        <input type="checkbox" name="status" id="status" class="first_color"
                                               @if($project->status == 1 ) checked="checked" @endif />
                                        <label for="status">Is Active?</label>
                                    </div>
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
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            var btd = $('#budget_topic_id').find(":selected").val();
            $.ajax({
                url: "{{route('ajax.get_monitoringOffice')}}",
                data: {
                    "budgetTopic": btd,
                },
                cache: false,
                type: "GET",
                success: function (response) {
                    var json = JSON.parse(response);
                    monitoringOffice = json;
                    $("#monitoring_office_id").val(json);
                },
                error: function (xhr) {

                }
            });

            var monitoringOffice = '';
            $('#district_id').change(function () {
                projectCode(monitoringOffice)
            });
            $('#update_project_code').on('click', function(){
                projectCode(monitoringOffice)
            })

            $('#budget_topic_id').change(function () {
                $btd = $('#budget_topic_id').find(":selected").val();
                $.ajax({
                    url: "{{route('ajax.get_monitoringOffice')}}",
                    data: {
                        "budgetTopic": $btd,
                    },
                    cache: false,
                    type: "GET",
                    success: function (response) {
                        var json = JSON.parse(response);
                        monitoringOffice = json;
                        $("#monitoring_office_id").val(json);
                    },
                    error: function (xhr) {

                    }
                });
            });

            var raheko = $(".swamittwo").val();
            if (raheko == 0) {
                $('#shitnumber').attr("disabled", 'disabled');
                $('#kittanumber').attr("disabled", 'disabled');
                $('#whose').attr("disabled", 'disabled');
            } else if (raheko == "1") {
                $('#whose').removeAttr('disabled');
                $('#shitnumber').attr("disabled", 'disabled');
                $('#kittanumber').attr("disabled", 'disabled');
            }
            else {
                $('#shitnumber').removeAttr('disabled');
                $('#kittanumber').removeAttr('disabled');
                $('#whose').attr("disabled", 'disabled');

            }

            $(".swamittwo").change(function () {
                var raheko = $(".swamittwo").val();
                if (raheko == 0) {
                    $('#shitnumber').attr("disabled", 'disabled');
                    $('#kittanumber').attr("disabled", 'disabled');
                    $('#whose').attr("disabled", 'disabled');
                } else if (raheko == "1") {
                    $('#whose').removeAttr('disabled');
                    $('#shitnumber').attr("disabled", 'disabled');
                    $('#kittanumber').attr("disabled", 'disabled');
                }
                else {
                    $('#shitnumber').removeAttr('disabled');
                    $('#kittanumber').removeAttr('disabled');
                    $('#whose').attr("disabled", 'disabled');

                }
            });

            var soilTest = $("#soiltest").val();
            if (soilTest != "2") {
                $('#baringcapacity').attr("disabled", 'disabled');
            }
            $("#soiltest").change(function () {
                soilTest = $("#soiltest").val();
                if (soilTest == "2") {
                    $('#baringcapacity').removeAttr('disabled');
                }
                else {
                    $('#baringcapacity').attr("disabled", 'disabled');
                }
            });
        });
        var $source = [@foreach($projects->orderBy('name','asc')->get() as $project) "{!! $project->name  !!}", @endforeach];
        var $name = $('#name');
        $name.tokenfield({
            autocomplete: {
                source: $source,
                delay: 100,
            },
            createTokensOnBlur: true,
            showAutocompleteOnFocus: true,
            delimiter: "%%%",
            limit: 1
        });
        preventDuplicate($name);

        var $source_eng = [@foreach($projects->orderBy('name_eng','asc')->get() as $project) " {!! $project->name_eng  !!} ", @endforeach];
        var $name_eng = $('#name_eng');
        $name_eng.tokenfield({
            autocomplete: {
                source: $source_eng,
                delay: 100,
            },
            createTokensOnBlur: true,
            showAutocompleteOnFocus: true,
            delimiter: "%%%",
            limit: 1
        });
        preventDuplicate($name_eng);

        function projectCode(monitoringOffice){
            $did = $('#district_id').find(":selected").val();
            $.ajax({
                url: "{{route('ajax.get.project.code')}}",
                data: {
                    "district_id": $did,
                    "monitoringOffice": monitoringOffice,
                    "add_one": true,
                },
                cache: false,
                type: "GET",
                success: function (response) {
                    var json = JSON.parse(response);
                    confirmProjectCodeChange(json.project_code);
                    $('#implementing_office_id').val(json.office_id).change();
                },
                error: function (xhr) {

                }
            });
        }
        function confirmProjectCodeChange(project_code){
            //if district or implementing office changes.. project code may change this fy
            swal({
                    title: "Project code change?",
                    text: "Are you sure to change the project code",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d9534f',
                    confirmButtonText: 'Yes, change it!',
                    cancelButtonText: "No, cancel plz!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if (isConfirm){
                        $("#project_code").val(project_code);
                    }
                }
            );
        }
    </script>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJQaz6AqTOGmByI43EqJd0SNEWz11FFjY&libraries=places"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/google_map.js')}}"></script>
@stop