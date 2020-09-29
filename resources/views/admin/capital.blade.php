@extends('layouts.admin_layout')

@section('content')
    <div class="pro-main-container container-fluid">
        <div class="home_banner col-md-12">
            <img src="{{asset('public/images/home_banner.png')}}" class="img-responsive" alt="Home Banner"/>
        </div>
        <div class="clearfix"></div>
        <hr style="border-bottom: thick solid #000"/>
        <br/>
        <div class="row dashboard_items">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('project.index')}}">
                    <i class="fa fa-money"></i>
                    <p>पूंजी निर्माण खर्च (२९३११-५११)</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-credit-card"></i>
                    <p>भैपरी आउने खर्च (२२९११)</p>
                </a>
            </div>
        </div>
    </div>
@stop