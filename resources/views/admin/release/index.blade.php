@extends('layouts.admin_layout')


@section('headerContent')
<link rel="stylesheet" href="{{asset('public/admin/plugin/lightbox/css/lightbox.css')}}" type="text/css" />
<style type="text/css">
    .implementing_office_show ul{
        list-style: none;
    }
    .implementing_office_show ul li a{
        display: block;
        border-bottom: thin solid #ccc;
        padding-left: 30px;
    }
    .implementing_office_show ul li a:hover{
        background: #ccc;
    }
</style>
@stop

@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                <li class="active"><a href="{{route('release.index')}}"><span class="glyphicon glyphicon-list"></span> Release</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="clearfix"></div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Choose Implementing Office</h3>
                                {!! minimizeButton('pro_collapse_implementing_office') !!}
                            </div>
                            <div class="panel-body collapse implementing_office_show" id="pro_collapse_implementing_office">
                                <ul>
                                    @foreach($implementingoffices as $implementingoffice)
                                        <li>
                                            @if($implementingoffice->is_physical_office ==1)
                                            <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                            @else
                                                <a href="#">{{$implementingoffice->name}}</a>
                                            @endif
                                            @if($implementingoffice->child()->count()>0)
                                                <ul>
                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                        <li>
                                                            @if($implementingoffice->is_physical_office ==1)
                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                            @else
                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                            @endif
                                                            @if($implementingoffice->child()->count()>0)
                                                                <ul>
                                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                                        <li>
                                                                            @if($implementingoffice->is_physical_office ==1)
                                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                            @else
                                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                                            @endif
                                                                            @if($implementingoffice->child()->count()>0)
                                                                                <ul>
                                                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                                                        <li>
                                                                                            @if($implementingoffice->is_physical_office ==1)
                                                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                                            @else
                                                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                                                            @endif
                                                                                            @if($implementingoffice->child()->count()>0)
                                                                                                <ul>
                                                                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                                                                        <li>
                                                                                                            @if($implementingoffice->is_physical_office ==1)
                                                                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                                                            @else
                                                                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                                                                            @endif
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            @endif
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-money"></i> पेस्की तथा निकाशको विवरण</h3>
                                {!! minimizeButton('pro_collapse_implementing_office') !!}
                            </div>
                            <div class="panel-body collapse in implementing_office_show" id="pro_collapse_implementing_office">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>मिति</th>
                                            <th>कार्यालय</th>
                                            <th style="width: 25%;">कार्यक्रमहरु</th>
                                            <th>जम्मा रकम</th>
                                            <th style="width:20%;">भुक्तानी विवरण</th>
                                            <th>अवस्था</th>
                                            <th>आवश्यक कार्य</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{$payment->release_date}}</td>
                                            <td>{{$payment->release()->first()->project->implementing_office->name}}</td>
                                            <td>
                                                <ul>
                                                    @foreach($payment->release as $release)
                                                        <li>{{$release->project->name}}({{$release->project->construction_type->name}})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                {{$payment->release()->sum('amount')}}
                                            </td>
                                            <td>
                                                @if($payment->payment_method == 'FCGO')
                                                    {{$payment->payment_detail}}
                                                @elseif($payment->payment_method == 'Account Payee')
                                                    माछापुच्छ्रे बैंक लिमिटेडको एकाउन्ट पेयी चेक नं {{$payment->cheque_no}}
                                                @else
                                                    बैंक डिपोजिट
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->status == 0)
                                                    <span class="label label-danger">Pending</span>
                                                @else
                                                    <span class="label label-success">Complete</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-info" href=""><span class="fa fa-search"></span></a>
                                                <a class="btn btn-xs btn-warning" href=""><span class="fa fa-pencil"></span></a>
                                                <a class="btn btn-xs btn-success" target="_blank" title="Print Letter" href="{{route('release.preview',$payment->id)}}"><span class="fa fa-file-text"></span></a>
                                                <a class="btn btn-xs btn-info" target="_blank" title="Print Cheque" href="{{route('release.cheque',$payment->id)}}"><span class="fa fa-money"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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