@extends('layouts.admin_layout')


@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css" />
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('user.index')}}"><i class="fa fa-buysellads"></i> Admins</a></li>
                    <li class="active"><a href="{{route('user.show',$user->slug)}}"><i class="fa fa-edit"></i> Edit User</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-4 col-md-6">
                        <a href="{{route('user.create')}}" class="btn btn-warning showToolTip" title="Add Admin" role="button" data-placement="top"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs hidden-sm">Add Admin</span></a>
                        <a href="{{route('user.index')}}" class="btn btn-info showToolTip" title="Edit Admins" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Admins</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-4 col-lg-4" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-user"></i> User Detail</h3>
                                {!! minimizeButton('pro_collapse_user') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user">
                                Name : {{ $user->name }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-image"></i> Image</h3>
                                {!! minimizeButton('pro_collapse_user_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user_image">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-car"></i> Stocks</h3>
                                {!! minimizeButton('pro_collapse_user_stocks') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_user_stocks">

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('footerContent')

@stop