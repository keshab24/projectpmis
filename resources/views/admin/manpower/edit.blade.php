@extends('layouts.admin_layout')

@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/css/selectize.min.css')}}" type="text/css" />
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('manpower.index')}}"><i class="fa fa-buysellads"></i> Manpower</a></li>
                    <li class="active"><a href="{{route('manpower.edit', $manpower->id)}}"><i class="fa fa-edit"></i> Edit Manpower</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('manpower.index')}}" class="btn btn-info showToolTip" title="Edit Manpower" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Manpowers</span></a>
                        <a href="{{route('manpower.create')}}" class="btn btn-warning showToolTip" title="Add Manpower" role="button" data-placement="top"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs hidden-sm">Add Manpower</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($manpower, ['route'=>['manpower.update', $manpower->id],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Manpower</h3>
                                {!! minimizeButton('pro_collapse_manpower') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_manpower">

                               <!-- title -->
                               <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                <div class="input-group pro_make_full showToolTip" @if($errors->has('title')) title="{!!$errors->first('title')!!}" @endif>
                                    {!! Form::label('title','Description:') !!}
                                    {!! Form::text('title', null, ['class'=>'form-control']) !!}
                                    @if($errors->has('title'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="titleStatus" class="sr-only">(error)</span>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="titleStatus" class="sr-only">(success)</span>
                                    @endif
                                </div>
                            </div>
                            <!-- type -->
                            <div class="form-group col-md-12 col-lg-12 @if($errors->has('type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                <div class="input-group pro_make_full showToolTip" @if($errors->has('type')) type="{!!$errors->first('type')!!}" @endif>
                                    {!! Form::label('type','Title:') !!}
                                    {!! Form::select('type', manpowerTypes(), null, ['class'=>'form-control','placeholder' => 'Assiotiated With','required']) !!}
                                    @if($errors->has('type'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="typeStatus" class="sr-only">(error)</span>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="typeStatus" class="sr-only">(success)</span>
                                    @endif
                                </div>
                            </div>
    
                            <!-- countable -->
                            <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                <input type="checkbox" name="countable" id="countable" class="first_color" {{ $manpower->countable ? 'checked' : '' }}/>
                                <label for="countable">Should Be Number?</label>
                            </div>
                             <!-- unit -->
                             <div class="form-group col-md-12 col-lg-12 @if($errors->has('unit')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                <div class="input-group pro_make_full showToolTip" @if($errors->has('unit')) title="{!!$errors->first('unit')!!}" @endif>
                                    {!! Form::label('unit','Unit:') !!}
                                    {!! Form::text('unit', null, ['class'=>'form-control']) !!}
                                    @if($errors->has('unit'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="unitStatus" class="sr-only">(error)</span>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="unitStatus" class="sr-only">(success)</span>
                                    @endif
                                </div>
                            </div>

                            <!-- description -->
                            <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                <div class="input-group pro_make_full showToolTip" @if($errors->has('description')) title="{!!$errors->first('description')!!}" @endif>
                                    {!! Form::label('description','Description:') !!}
                                    {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'8']) !!}
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
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_manpower_save') !!}
                            </div>
                            <!-- categories -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('categories')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('categories', 'Categories', ['class' => 'form-control-label']) !!}
                                    {!! Form::select('categories[]', $categories, null, ['class'=>'form-control selectize '.($errors->has('categories')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'placeholder' => 'Choose /Add Categories','multiple']) !!}
                                    @if($errors->has('categories'))
                                        <div class="form-control-feedback">{{$errors->first('categories')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_manpower_save">
                                <div class="form-group col-md-6 col-lg-6">
                                    {!! Form::label('order','Order:') !!}
                                    {!! Form::input('number','order', null, ['class'=>'form-control']) !!}
                                </div>
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" @if($manpower->status == 1 ) checked="checked" @endif />
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
    <script type="text/javascript" src="{{asset('public/js/selectize.min.js')}}"></script>
    <script>
        $('.selectize').selectize({
            delimiter: ',',
            persist: false,
            create: function (input) {
                return {
                    value: input,
                    text: input
                }
            }
        })
    </script>
@stop