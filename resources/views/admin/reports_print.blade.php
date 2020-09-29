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
            <li class="active"><a href="{{route('reports_print')}}"><span class="glyphicon glyphicon-list"></span> Reports Print</a></li>
        </ol>
        <br/>
        <div class="row dashboard_items">
            <ul>
                <li><a href="{{route('report.progress')}}"><i class="fa fa-line-chart"></i> प्रगति </a></li>
                <li><a href="{{route('report.allocation')}}"><i class="fa fa-bank"></i> नयाँ कार्यक्रम </a></li>
                <li><a href="{{route('report.amendment')}}"><i class="fa fa-exchange"></i> संशोधन प्रतिबेदन </a></li>
                <li><a href="{{route('summary')}}"><i class="fa fa-file-text-o"></i> बजेट गत सरंस </a></li>
                <li><a href="{{route('moreThanFifteenCorerSummary')}}"><i class="fa fa-file-text-o"></i> १५ करोड माथिको आयोजना </a></li>
                <li><a href="{{route('divisionSummary')}}"><i class="fa fa-file-text-o"></i> डिभिजन गत सरंस </a></li>>
            </ul>
        </div>
    </div>
@stop