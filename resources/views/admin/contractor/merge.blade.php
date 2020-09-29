
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
                <li class="active"><a href="{{route('contractor.index')}}"><span class="glyphicon glyphicon-list"></span> contractor</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="col-md-12">
                    <div class="row">
                        {!!Form::open(['route'=>'contractor_merge','method'=>'post','class'=>'showSavingOnSubmit','files'=>true,'onsubmit'=>"return confirm('Do you really want to Merge the contractors.')"])!!}
                            <div class="col-md-6">
                                Be Replaced with
                                {!! Form::select('replace_with',$contractors,null,['class'=>'form-control']) !!}
                            </div>
                            <div class="col-md-6">
                                To be removed
                                {!! Form::select('old_contractor',$contractors,null,['class'=>'form-control']) !!}
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="col-md-12">
                                <input type="submit" value="Merge" class="btn btn-success">
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footerContent')
<script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/plugin/lightbox/js/lightbox.js')}}"></script>

@stop