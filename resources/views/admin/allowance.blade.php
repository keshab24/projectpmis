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
                <a href="{{route('allowance')}}">
                    <i class="fa fa-credit-card"></i>
                    <p>भत्ता(२१११९)</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('procurement.index')}}">
                    <i class="fa fa-bank"></i>
                    <p>पोशाक भत्ता (२११२१)</p>
                </a>
            </div>
        </div>
    </div>
@stop