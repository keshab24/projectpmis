@extends('layouts.admin_layout')
@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('expendituretopic.index')}}"><i class="fa fa-buysellads"></i> Expenditure Topic</a></li>
                    <li class="active"><a href="{{route('expendituretopic.create')}}"><i class="fa fa-plus"></i> Add Expenditure Topic</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('expendituretopic.index')}}" class="btn btn-info showToolTip" title="Edit Expenditure Topic" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Expenditure Topics</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::open(['route'=>'expendituretopic.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Expenditure Topic</h3>
                                {!! minimizeButton('pro_collapse_expendituretopic') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_expendituretopic">


                                <!-- expenditure_topic_num -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('expenditure_topic_num')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('expenditure_topic_num','ख.उ.शी.नं.:') !!}
                                        {!! Form::text('expenditure_topic_num', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('expenditure_topic_num'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_topic_numStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('expenditure_topic_num') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_topic_numStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- expenditure_head -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('expenditure_head')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('expenditure_head','खर्च उप-शीर्षक नाम:') !!}
                                        {!! Form::text('expenditure_head', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('expenditure_head'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_headStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('expenditure_head') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_headStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- expenditure_head_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('expenditure_head_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('expenditure_head_eng','Expenditure Head Name (English):') !!}
                                        {!! Form::text('expenditure_head_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('expenditure_head_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_head_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('expenditure_head_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="expenditure_head_engStatus" class="sr-only">(success)</span>
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
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span> Choose Parent Expenditure Topic <span class="glyphicon glyphicon-info-sign showInfo faa-bounce animated-hover" title="Choose Parent Expenditure Topic" data-content="Choose the Expenditure Topic under which this topic exists" data-trigger="focus" data-placement="top" data-toggle="popover" tabindex="0" role="button"></span></h3>
                                {!! minimizeButton('pro_collapse_tree') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_tree">
                                <ul class="tree">
                                    <li>
                                        <div class="click_menu click_menu_active" data-menu-id="1" data-menu-level="0" data-menu-order="0">
                                            <span class="glyphicon glyphicon-th"></span> Root
                                        </div>
                                        <ul>
                                            @foreach($expenditure_topics as $expenditure_topic)
                                                @if($expenditure_topic->level == 1)
                                                    <li>
                                                        <div class="click_menu" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                            <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
                                                        </div>
                                                        @if(count($expenditure_topic->child)>0)
                                                            <ul>
                                                                @foreach($expenditure_topic->child as $expenditure_topic)
                                                                    @if($expenditure_topic->level == 2)
                                                                        <li>
                                                                            <div class="click_menu" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                                                <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
                                                                            </div>
                                                                            @if(count($expenditure_topic->child)>0)
                                                                                <ul>
                                                                                    @foreach($expenditure_topic->child as $expenditure_topic)
                                                                                        @if($expenditure_topic->level == 3)
                                                                                            <li>
                                                                                                <div class="click_menu" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                                                                    <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
                                                                                                </div>
                                                                                                @if(count($expenditure_topic->child)>0)
                                                                                                    <ul>
                                                                                                        @foreach($expenditure_topic->child as $expenditure_topic)
                                                                                                            @if($expenditure_topic->level == 4)
                                                                                                                <li>
                                                                                                                    <div class="click_menu" data-menu-id="{!! $expenditure_topic['id'] !!}" data-menu-level="{!! $expenditure_topic['level'] !!}" data-menu-order="{!! $expenditure_topic['order'] !!}">
                                                                                                                        <span class="glyphicon glyphicon-arrow-right"></span> {!! $expenditure_topic['expenditure_head'] !!}({!! $expenditure_topic['expenditure_topic_num'] !!})
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

                                {!! Form::hidden('expenditure_topic_id','1',['id'=>'expenditure_topic_id']) !!}
                                {!! Form::hidden('level','1',['id'=>'level']) !!}
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_expendituretopic_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_expendituretopic_save">
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
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function() {

        });
    </script>
@stop