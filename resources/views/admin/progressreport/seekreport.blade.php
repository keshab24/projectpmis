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
                            {!!Form::open(['route'=>'hbms.seekProject.post','method'=>'post','files'=>true])!!}
                            <table class="table table-striped table-hover col-offset-6 centered">
                                <tr>
                                    <th>
                                        बजेट उप - शीर्षक नं 
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('budget_topic_id',$budgettopics,null,['class'=>'form-control','placeholder'=>'Any']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        संचालन / निरीक्षण गर्ने कार्यालय
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('implementing_office_id',$implementingoffices,null,['class'=>'form-control','id'=>'implementing_office_id','placeholder'=>'कार्यालय']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        कार्यक्रम   सुरु  भएको  आ.व 
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                {!! Form::select('fiscalYear_from',$fiscalYear,null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="col-sm-1">
                                                <p><strong>देखि</strong></p>
                                            </div>
                                            <div class="col-sm-5">
                                                {!! Form::select('fiscalYear_to',$fiscalYear,session()->get('pro_fiscal_year'),['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        म्याद थप भएको अवधि 
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('project_period',['1'=>'1 (100% or more)','1.5'=>'1.5 (150% or more)','2'=>'2 (200% or more)','2.5'=>'2.5 (250% or more)','3'=>'3 (300% or more)'],null,['class'=>'form-control','placeholder'=>'म्याद थप भएको अवधि']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        कति पटक म्याद  थप  भएको
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('time_ext_count',['not_extended'=>'0 (म्याद थप नभएका)', 1=>'1 or more','2 or more','3 or more','4 or more'],null,['class'=>'form-control','placeholder'=>'कति पटक म्याद  थप  भएको']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::radio('visibility',0,false) !!} Excel
                                        {!! Form::radio('visibility',1,true) !!} Table
                                    </td>
                                </tr>
                            </table>
                            <div class="panel panel-default">
                                <div class="panel-footer">
                                    <button class="btn btn-success">Submit to Proceed</button>
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