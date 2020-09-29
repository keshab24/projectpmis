@extends('layouts.admin_layout')
@section('headerContent')

@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('user.index')}}"><i class="fa fa-buysellads"></i> Admin</a></li>
                    <li class="active"><a href="{{route('user.edit',$user->slug)}}"><i class="fa fa-edit"></i> Edit Admin</a></li>

                    @if($user->relatedLink())
                      <li><a href="{{$user->relatedLink()['route']}}"><i class="fa fa-eye"></i> {{ $user->relatedLink()['related'] }}</a></li>
                    @endif
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
                    {!!Form::model($user,['route'=>['user.password.change.store',$user->slug],'method'=>'put','class'=>'showSavingOnSubmit'])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-edit"></span> Edit Admin <span></span></h3>
                                {!! minimizeButton('pro_collapse_user') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user">

                              <!-- password -->
                              <div class="form-group col-md-6 col-lg-6 @if($errors->has('password')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                <div class="input-group pro_make_full showToolTip" @if($errors->has('password')) title="{!!$errors->first('password')!!}" @endif>
                                    {!! Form::label('password','Password:') !!}
                                    {!! Form::password('password', ['class'=>'form-control','required']) !!}
                                    @if($errors->has('password'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="passwordStatus" class="sr-only">(error)</span>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="passwordStatus" class="sr-only">(success)</span>
                                    @endif
                                </div>
                            </div>

                            <!-- password_confirmation -->
                            <div class="form-group col-md-6 col-lg-6 @if($errors->has('password_confirmation')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                <div class="input-group pro_make_full showToolTip" @if($errors->has('password_confirmation')) title="{!!$errors->first('password_confirmation')!!}" @endif>
                                    {!! Form::label('password_confirmation','Password Again:') !!}
                                    {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
                                    @if($errors->has('password_confirmation'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="password_confirmationStatus" class="sr-only">(error)</span>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="password_confirmationStatus" class="sr-only">(success)</span>
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
                                {!! minimizeButton('pro_collapse_user_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user_save">
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

@stop