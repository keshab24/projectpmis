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
                    <i class="fa fa-globe"></i>
                    <p>तालिम कार्यक्रम खर्च</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-plug"></i>
                    <p>धारा तथा बिजुली</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-mobile-phone"></i>
                    <p>टेलिफोन महसुल</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-building-o"></i>
                    <p>कार्यालय सम्बन्धि खर्च</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-renren"></i>
                    <p>भाडा</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-hand-o-right"></i>
                    <p>मर्मत तथा सम्भार</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-battery-half"></i>
                    <p>सबारी इन्धन तथा अन्य इन्धन</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-comments"></i>
                    <p>परामर्स तथा अन्य सेवा सुल्क</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-credit-card"></i>
                    <p>बिबिध खर्च</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-tasks"></i>
                    <p>कार्यक्रम खर्च</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <a href="{{route('allowance')}}">
                    <i class="fa fa-tasks"></i>
                    <p>कार्यक्रम भ्रमण खर्च</p>
                </a>
            </div>
        </div>
    </div>
@stop