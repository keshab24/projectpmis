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
                            {!!Form::open(['route'=>'hbms.completed_projects.post','method'=>'POST','class'=>'showSavingOnSubmit','files'=>true])!!}
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
                                        सुरु भएको  आ.ब
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('fy_id',add_my_array($fiscalYear,"Any"),null,['class'=>'form-control','id'=>'fy_id']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        कार्यक्रमको स्थिति
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::radio('project_status',0,1,['class'=>'project_status']) !!} Running
                                        {!! Form::radio('project_status',1,0,['class'=>'project_status']) !!} Work Completed / Handover
                                    </td>
                                </tr>
                                <tr>
                                    <td class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                        <input type="checkbox" name="detailed_report" id="status" class="first_color">
                                        <label for="status">Detail report</label>
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