@extends('layouts.admin_layout')
@section('headerContent')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li class="active"><a href="{{route("$route.index")}}"><span class="glyphicon glyphicon-list"></span> Web Message</a></li>
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route("$route.index")}}" class="btn btn-info showToolTip" title="Show Web Message" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Web Message</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-8 col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    Message</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice_save">
                                <h1>{{$message->title}}</h1>
                                <h2>{{ $message->project_code}}</h2>
                                {!!Form::open(['route'=>["sync_web_message",$message->id],'method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                                <div class="form-group col-md-12 col-lg-12 pro_checkbox pro_no_top">
                                    {!! Form::select('offices[]', $offices, $message->implementingOffices->pluck('id')->toArray(), ['multiple','class'=>'form-control']) !!}
                                </div>
                                <div class="form-group col-md-12 col-lg-12 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="send_to_all" id="status" class="first_color">
                                    <label for="status">Send to all?</label>
                                </div>
                                <div class="form-group col-md-12 col-lg-12 pro_checkbox pro_no_top">
                                    <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                        Save
                                    </button>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/editor.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function () {

        });

    </script>

@stop


