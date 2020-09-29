
@extends('layouts.admin_layout')


@section('headerContent')
<link rel="stylesheet" href="{{asset('public/admin/plugin/lightbox/css/lightbox.css')}}" type="text/css" />
@stop

@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                <li class="active"><a href="{{route('allLog')}}"><span class="glyphicon glyphicon-list"></span> Logs</a></li>
            </ol>
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">

                <div class="col-md-12 col-lg-12">
                    {{ crud()[$log->status] ?? '' }} by {{ $log->user->name }} ({{ $log->base_model }}) @if($log->project) {{ $log->project->name }} @endif
                    {!! $log->description !!}
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footerContent')
<script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/plugin/lightbox/js/lightbox.js')}}"></script>
<script>
    $('table').addClass('table table-striped table-hover');
</script>
@stop