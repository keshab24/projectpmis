
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
                    <form action="{{ route('searchLogs') }}" method="get">
                        <input type="text" value="{{ isset($_GET['search'])?$_GET['search']:'' }}" placeholder="Search" name="search">
                        <input type="submit">
                    </form>
                        <table class="table table-striped">
                            @if(isset($onlyProjectsDetail))
                                <thead>
                                <tr>
                                    <th width="3%">S.No</th>
                                    <th width="7%">Date</th>
                                    <th width="10%">Project Code</th>
                                    <th width="40%">Project Name</th>
                                    <th>Implementing Office</th>
                                    <th>District</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logs as $index=>$log)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ dateBS($log->created_at->format('Y-m-d')) }}</td>
                                        <td>{{ $log->project->project_code }}</td>
                                        <td> <a href="{{ route('project.show',$log->project->id ) }}">{{ $log->project->name }}</a></td>
                                        <td>{{ $log->project->implementing_office->name}}</td>
                                        <td>{{ $log->project->district->name}}</td>
                                        <td><a class="btn btn-xs btn-warning" href="{{ route('detailLog',$log->id) }}">Detail</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                @else
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Model</th>
                                        <th>Implementing Office</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->user->name }}</td>
                                        <td>{{ crud()[$log->status] ?? '' }}</td>
                                        <td> <a href="{{ route('detailLog',$log->id) }}">{{ $log->base_model }}</a></td>
                                        <td>{{ $log->project?$log->project->implementing_office->name:"" }}</td>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                        <td><a class="btn btn-xs btn-warning" href="{{ route('detailLog',$log->id) }}">Detail</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">None</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            @endif
                        </table>
                    <div class="clearfix"></div>
                    {!! $logs->appends(Request::only('search'))->render() !!}
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