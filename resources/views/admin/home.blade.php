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
            {{--<div class="col-lg-4 col-md-4 col-sm-4col-xs-4">
                <a href="{{route('income.create')}}">
                    <i class="fa fa-bank"></i>
                    <p>आम्दानी</p>
                </a>
            </div>--}}
            {{--<div class="col-lg-4 col-md-4 col-sm-4col-xs-4">
                <a href="{{route('expense.create')}}">
                    <i class="fa fa-money"></i>
                    <p>खर्च</p>
                </a>
            </div>--}}
            <div class="col-lg-12 col-md-12 col-sm-4col-xs-12">
                <a href="{{route('program')}}">
                    <i class="fa fa-book"></i>
                    <p>कार्यक्रम</p>
                </a>
            </div>
        </div>
    </div>
@stop