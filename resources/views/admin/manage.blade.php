@extends('layouts.admin_layout')

@section('content')
    <div class="pro-main-container container-fluid">
        <div class="home_banner col-md-12">
            <img src="{{asset('public/images/home_banner.png')}}" class="img-responsive" alt="Home Banner"/>
        </div>
        <div class="clearfix"></div>
        <hr style="border-bottom: thick solid #000"/>
        <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
            <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
            <li class="active"><a href="{{route('manage')}}"><span class="glyphicon glyphicon-list"></span> Manage</a></li>
        </ol>
        <br/>
        <div class="row dashboard_items">
            <ul>
                <li><a href="{{route('budgettopic.index')}}"><i class="fa fa-list-alt"></i> बजेट  शीर्षक </a></li>
                <li><a href="{{route('expendituretopic.index')}}"><i class="fa fa-list-alt"></i> खर्च शीर्षक</a></li>
                <li><a href="{{route('region.index')}}"><i class="fa fa-list-alt"></i> Regions </a></li>
                <li><a href="{{route('zone.index')}}"><i class="fa fa-list-alt"></i> Zones </a></li>
                <li><a href="{{route('district.index')}}"><i class="fa fa-list-alt"></i> Districts </a></li>
                <li><a href="{{route('division.index')}}"><i class="fa fa-list-alt"></i> Divisions </a></li>
                <li><a href="{{route('address.index')}}"><i class="fa fa-list-alt"></i> Address </a></li>
                <li><a href="{{route('divisionchief.index')}}"><i class="fa fa-list-alt"></i> Division Chief </a></li>
                <li><a href="{{route('month.index')}}"><i class="fa fa-list-alt"></i> Month </a></li>
                <li><a href="{{route('fiscalyear.index')}}"><i class="fa fa-list-alt"></i> Fiscal Year </a></li>
                <li><a href="{{route('constructiontype.index')}}"><i class="fa fa-list-alt"></i> Construction Type </a></li>
                <li><a href="{{route('progresstrack.index')}}"><i class="fa fa-list-alt"></i> Progress Track </a></li>
                <li><a href="{{route('sector.index')}}"><i class="fa fa-list-alt"></i> Sector </a></li>
                <li><a href="{{route('implementingoffice.index')}}"><i class="fa fa-list-alt"></i> Implementing Office </a></li>
                <li><a href="{{route('implementingmode.index')}}"><i class="fa fa-list-alt"></i> Procurement Type </a></li>
            </ul>
        </div>
    </div>
@stop