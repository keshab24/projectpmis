
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
                <li class="active"><a href="{{route('district.index')}}"><span class="glyphicon glyphicon-list"></span> District</a></li>
            </ol>
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="col-md-12 col-lg-12">
                    <div class="">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th width="20%">
                                    Name
                                </th>
                                <th width="10%">
                                    Date
                                </th>
                            </tr>
                            @foreach($files as $file)
                            <tr>
                                <td><a href="{{ route('get_backup_resources_download',['dbbackup',$file]) }}" class="fa fa-4x fa-database" aria-hidden="true"></a></td>
                                <td> {{ trim(trim(strstr($file,'p-'),'.sql'),'p-')}} </td>
                            </tr>
                            @endforeach
                            @foreach($folders as $file)
                            <tr>
                                <td><a href="{{ route('get_backup_resources_download',['zip',$file]) }}" class="fa fa-4x fa-folder" aria-hidden="true"></a></td>
                                <td> {{ $file}} </td>
                            </tr>
                            @endforeach
                        </table>

                    </div>
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

@stop