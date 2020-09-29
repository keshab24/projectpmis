@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('project-group.index')}}"><i class="fa fa-buysellads"></i> Construction Type</a></li>
                    <li class="active"><a href="{{route('project-group.create')}}"><i class="fa fa-plus"></i> Add Construction Type</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('project-group.index')}}" class="btn btn-info showToolTip" title="Edit Construction Type" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Construction Type</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($projectGroup, ['route'=>['project-group.update', $projectGroup->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Project Group/Sub Group</h3>
                                {!! minimizeButton('pro_collapse_project-group') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_project-group">
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','Name:') !!}
                                        {!! Form::text('name', Null,['class'=>'form-control']) !!}
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

                                <!-- name_nep -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_nep')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name_nep','नाम:') !!}
                                        {!! Form::text('name_nep', Null,['class'=>'form-control']) !!}
                                        @if($errors->has('name_nep'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_nepStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('name_nep') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_nepStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- description -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::text('description', Null,['class'=>'form-control']) !!}
                                        @if($errors->has('description'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('description') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- description_nep -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description_nep')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description_nep','कैफियत:') !!}
                                        {!! Form::text('description_nep', Null,['class'=>'form-control']) !!}
                                        @if($errors->has('description_nep'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="description_nepStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('description_nep') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="description_nepStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- budget_topic -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('budget_topic')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('budget_topic','Budget Topic') !!}
                                        {!! Form::select('budget_topic',$budgettopics ,null, ['class'=>'form-control']) !!}
                                        @if($errors->has('budget_topic'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_topicStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('budget_topic') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="budget_topicStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span> Choose Parent Group <span class="glyphicon glyphicon-info-sign showInfo faa-bounce animated-hover" title="Choose Parent Office" data-content="Choose the office under which this office exists" data-trigger="focus" data-placement="top" data-toggle="popover" tabindex="0" role="button"></span></h3>
                                {!! minimizeButton('pro_collapse_tree') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_tree">
                                <ul class="tree">
                                    <li>
                                        <div class="click_menu @if($projectGroup->group_category_id == 1)click_menu_active @endif" data-menu-id="1" data-menu-level="0" data-menu-order="0">
                                            <span class="glyphicon glyphicon-th"></span> Root
                                        </div>
                                        <ul>

                                            @foreach($project_groups as $project_group)
                                                @if($project_group->level == 1)
                                                    <li>
                                                        <div class="click_menu @if($projectGroup->group_category_id == $project_group->id)click_menu_active @endif" data-menu-id="{!! $project_group['id'] !!}" data-menu-level="{!! $project_group['level'] !!}" data-menu-order="{!! $project_group['order'] !!}">
                                                            <span class="glyphicon glyphicon-arrow-right"></span> {!! $project_group['name'] !!}
                                                        </div>
                                                        @if(count($project_group->child)>0)
                                                            <ul>
                                                                @foreach($project_group->child as $project_group)
                                                                    @if($project_group->level == 2)
                                                                        <li>
                                                                            <div class="click_menu @if($projectGroup->group_category_id == $project_group->id)click_menu_active @endif" data-menu-id="{!! $project_group['id'] !!}" data-menu-level="{!! $project_group['level'] !!}" data-menu-order="{!! $project_group['order'] !!}">
                                                                                <span class="glyphicon glyphicon-arrow-right"></span> {!! $project_group['name'] !!}
                                                                            </div>
                                                                            @if(count($project_group->child)>0)
                                                                                <ul>
                                                                                    @foreach($project_group->child as $project_group)
                                                                                        @if($project_group->level == 3)
                                                                                            <li>
                                                                                                <div class="click_menu" data-menu-id="{!! $project_group['id'] !!}" data-menu-level="{!! $project_group['level'] !!}" data-menu-order="{!! $project_group['order'] !!}">
                                                                                                    <span class="glyphicon glyphicon-arrow-right"></span> {!! $project_group['name'] !!}
                                                                                                </div>
                                                                                                @if(count($project_group->child)>0)
                                                                                                    <ul>
                                                                                                        @foreach($project_group->child as $project_group)
                                                                                                            @if($project_group->level == 4)
                                                                                                                <li>
                                                                                                                    <div class="click_menu" data-menu-id="{!! $project_group['id'] !!}" data-menu-level="{!! $project_group['level'] !!}" data-menu-order="{!! $project_group['order'] !!}">
                                                                                                                        <span class="glyphicon glyphicon-arrow-right"></span> {!! $project_group['name'] !!}
                                                                                                                    </div>
                                                                                                                </li>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                @endif
                                                                                            </li>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                                <br />
                                <br />
                                <!-- order -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('order')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group col-md-12 col-lg-12 showToolTip" @if($errors->has('order')) title="{!!$errors->first('order')!!}" @endif>
                                        {!!Form::label('order','Order:')!!}
                                        {!!Form::text('order', 0, ['class'=>'form-control','id'=>'menu_order'])!!}
                                        @if($errors->has('order'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="orderStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="orderStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                {!! Form::hidden('group_category_id','1',['id'=>'group_category_id']) !!}
                                {!! Form::hidden('level','1',['id'=>'level']) !!}
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_project-group_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_project-group_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" checked="checked" />
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
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function() {

        });
    </script>
@stop