@extends('layouts.admin_layout')

@section('content')
    <div class="pro-main-container container-fluid">
        <div class="home_banner row">
            <br>
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <img src="{{asset('admin/images/home_banner.png')}}" class="img-responsive" alt="Home Banner"/>
            </div>
            <div class="col-md-6">
                <h1 style="color: #d2000b" class="text-left">
                    {{ Auth::User()->implementingOffice->name }}
                </h1>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr style="border-bottom: thick solid #000"/>
        <br/>
        <div class="row dashboard_items">
{{--            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">--}}
{{--                <a href="{{route('form.amendment')}}">--}}
{{--                    <i class="fa fa-book"></i>--}}
{{--                    <p>संशोधन फाराम</p>--}}
{{--                </a>--}}
{{--            </div>--}}
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                <a href="{{route('project.index')}}">
                    <i class="fa fa-book"></i>
                    <p>कार्यक्रम</p>
                </a>
            </div>

            <div  {{ restrictToImplementingOffice() }} class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                <a href="{{route('implementingoffice.index')}}">
                    <i class="fa fa-bank"></i>
                    <p>कार्यान्वयन निकाय</p>
                </a>
            </div>

{{--            <div {{ restrictToImplementingOffice() }} class="col-lg-3 col-md-3 col-sm-4 col-xs-6">--}}
{{--                <a href="{{route('allocation.index')}}">--}}
{{--                    <i class="fa fa-exchange"></i>--}}
{{--                    <p>बजेट बाँडफांड/संशोधन</p>--}}
{{--                </a>--}}
{{--            </div>--}}

{{--            <div {{ restrictToImplementingOffice() }} class="col-lg-3 col-md-3 col-sm-4 col-xs-6">--}}
{{--                <a href="{{route('lumpsumbudget.index')}}">--}}
{{--                    <i class="fa fa-briefcase"></i>--}}
{{--                    <p>एक्मुस्ठ बजेट भण्डार</p>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div {{ restrictToImplementingOffice() }} class="col-lg-3 col-md-3 col-sm-4 col-xs-6">--}}
{{--                <a href="{{route('release.index')}}">--}}
{{--                    <i class="fa fa-exchange"></i>--}}
{{--                    <p>निकाशा</p>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div {{ restrictToImplementingOffice() }} class="col-lg-3 col-md-3 col-sm-4 col-xs-6">--}}
{{--                <a href="{{route('progress.index')}}">--}}
{{--                    <i class="fa fa-line-chart"></i>--}}
{{--                    <p>प्रगति</p>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div {{ restrictToImplementingOffice() }} class="col-lg-3 col-md-3 col-sm-4 col-xs-6">--}}
{{--                <a href="{{route('reports_print')}}">--}}
{{--                    <i class="fa fa-file-text-o"></i>--}}
{{--                    <p>प्रतिबेदन</p>--}}
{{--                </a>--}}
{{--            </div>--}}
        </div>
    </div>
@stop