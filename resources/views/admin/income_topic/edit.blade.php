@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('expendituretopic.index')}}"><i class="fa fa-buysellads"></i> Income Topic</a></li>
                    <li class="active"><a href="{{route('income-topic.edit', $incomeTopic->slug)}}"><i class="fa fa-edit"></i> Edit Income Topic</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('income-topic.index')}}" class="btn btn-info showToolTip" title="Show Income Topic" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Income Topics</span></a>
                        <a href="{{route('income-topic.create')}}" class="btn btn-warning showToolTip" title="Add Income Topic" role="button" data-placement="top"><span class="glyphicon glyphicon-edit"></span> <span class="hidden-xs hidden-sm">Add Income Topic</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($incomeTopic, ['route'=>['income-topic.update', $incomeTopic->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Income Topic</h3>
                                {!! minimizeButton('pro_collapse_incomeTopic') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_incomeTopic">

                                <!-- code -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('code')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('code','आम्दानी नं:') !!}
                                        {!! Form::text('code', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('code'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="codeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('code') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="codeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','आम्दानी शिर्षक:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
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

                                <!-- name_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name_eng','Income Topic:') !!}
                                        {!! Form::text('name_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('name_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('name_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('description')) title="{!!$errors->first('description')!!}" @endif>
                                        {!! Form::label('description','Description:') !!}
                                        <div id="result_description">
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
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span> Choose Parent Income Topic <span class="glyphicon glyphicon-info-sign showInfo faa-bounce animated-hover" title="Choose Parent Income Topic" data-content="Choose the Income Topic under which this topic exists" data-trigger="focus" data-placement="top" data-toggle="popover" tabindex="0" role="button"></span></h3>
                                {!! minimizeButton('pro_collapse_tree') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_tree">
                                <ul class="tree">
                                    <li>
                                        <div class="click_menu @if($incomeTopic->parent_id == 1)click_menu_active @endif" data-menu-id="1" data-menu-level="0" data-menu-order="0">
                                            <span class="glyphicon glyphicon-th"></span> Root
                                        </div>
                                        <ul>
                                            @foreach($income_topics as $income_topic)
                                                @if($income_topic->level == 1)
                                                    <li>
                                                        <div class="click_menu @if($incomeTopic->parent_id == $income_topic->id)click_menu_active @endif" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                            <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
                                                        </div>
                                                        @if(count($income_topic->child)>0)
                                                            <ul>
                                                                @foreach($income_topic->child as $income_topic)
                                                                    @if($income_topic->level == 2)
                                                                        <li>
                                                                            <div class="click_menu @if($incomeTopic->parent_id == $income_topic->id)click_menu_active @endif" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                                                <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
                                                                            </div>
                                                                            @if(count($income_topic->child)>0)
                                                                                <ul>
                                                                                    @foreach($income_topic->child as $income_topic)
                                                                                        @if($income_topic->level == 3)
                                                                                            <li>
                                                                                                <div class="click_menu @if($incomeTopic->parent_id == $income_topic->id)click_menu_active @endif" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                                                                    <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
                                                                                                </div>
                                                                                                @if(count($income_topic->child)>0)
                                                                                                    <ul>
                                                                                                        @foreach($income_topic->child as $income_topic)
                                                                                                            @if($income_topic->level == 4)
                                                                                                                <li>
                                                                                                                    <div class="click_menu @if($incomeTopic->parent_id == $income_topic->id)click_menu_active @endif" data-menu-id="{!! $income_topic['id'] !!}" data-menu-level="{!! $income_topic['level'] !!}" data-menu-order="{!! $income_topic['order'] !!}">
                                                                                                                        <span class="glyphicon glyphicon-arrow-right"></span> {!! $income_topic['name'] !!}({!! $income_topic['code'] !!})
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
                                        {!!Form::text('order', null, ['class'=>'form-control','id'=>'menu_order'])!!}
                                        @if($errors->has('order'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="orderStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="orderStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                {!! Form::hidden('income_topic_id',$incomeTopic->parent_id,['id'=>'income_topic_id']) !!}
                                {!! Form::hidden('level',$incomeTopic->level,['id'=>'level']) !!}
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_incomeTopic_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_incomeTopic_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" @if($incomeTopic->status == 1 ) checked="checked" @endif />
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
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function() {

        });
    </script>
@stop