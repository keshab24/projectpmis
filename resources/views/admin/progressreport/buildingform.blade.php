
@extends('layouts.admin_layout')

@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        <div class="col-lg-2 col-md-2 col-sm-2">

                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-5">

                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-5">

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <div class="table-responsive pro_hide_y centered">
                            {!!Form::open(['route'=>'building.report.view','method'=>'get','class'=>'showSavingOnSubmit','files'=>true])!!}
                            <table class="table table-striped table-hover col-offset-6 centered">
                                <tr>
                                    <th>
                                        बजेट शिर्षक
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('budget_topic_id',$budgettopics,null,['class'=>'form-control']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        संचालन / निरीक्षण गर्ने कार्यालय
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('implementing_office_id',$implementingoffices,null,['class'=>'form-control','id'=>'implementing_office_id']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        आयोजनाको स्थिति अनुसार खोज्नुस
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('status',$statuses,4,['class'=>'form-control','id'=>'status']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        आयोजनाको स्थिति अनुसार खोज्नुस
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('storey_area',getStoreyArea(),null,['class'=>'form-control','id'=>'status']) !!}
                                    </td>
                                </tr>
                            </table>
                            <div class="panel panel-default">
                                <div class="panel-footer">
                                    <button class="btn btn-success" data-loading-text="Processing..." autocomplete="off">Submit to Proceed</button>
                                    <button class="btn btn-default" type="reset">Reset</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript">
        $(document).ready(function(){
            @if(session()->has('lump_sum_budget_issue'))
                swal('Please set lump sum budget for \n Budget Topic No. {{session()->get('lump_sum_budget_issue')}}, first!!!');
            @endif
        });
    </script>

@stop