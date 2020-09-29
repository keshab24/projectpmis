@extends('layouts.admin_layout')


@section('headerContent')
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('summary')}}"><i class="fa fa-file-text-0"></i> Summary Report</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                {!! Form::open(['route'=>'divisionSummary','method'=>'get','id'=>'pro_helper_form']) !!}
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <?php
                                        $budgetTopicId=key($budgetTopics->toArray());
                                        if(isset($_GET['budget_topic'])){
                                            if($_GET['budget_topic']!='' || $_GET['budget_topic']!= null){
                                                $budgetTopicId=$_GET['budget_topic'];
                                            }
                                        }
                                        ?>
                                        {!! Form::select('budget_topic',$budgetTopics,$budgetTopicId,['class'=>'form-control', 'id'=>'pro_budget_topic_id']) !!}
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                        {{--{!! Form::select('expenditure_topic_id',$expenditure_topics,$expenditure_topic_id,['class'=>'form-control', 'id'=>'pro_expenditure_topic_id']) !!}--}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        {{--{!! Form::select('implementing_office_id',$implementing_offices,$implementing_office_id,['class'=>'form-control','id'=>'pro_implementing_office_id']) !!}--}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        {{--{!! Form::select('division_code',$division_codes,$division_code,['class'=>'form-control pro_submit_form pro_hide_me pro_division_code','disabled'=>'disabled']) !!}--}}
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-info btn-xs">Switch</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div id="dvData">
                            <h1>डिभीजन अनुसारको सारांस प्रगति</h1>
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>कार्यान्वयन निकाय</th>
                                    <th>कार्यक्रम संख्या</th>
                                    <th>बार्षिक बजेट</th>
                                    <th>प्रथम चौमासिक</th>
                                    <th>दोस्रो चौमासिक</th>
                                    <th>तेस्रो चौमासिक</th>
                                    <th>नेपाल सरकार</th>
                                    <th>अनुदान</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_project_budget = 0;
                                $total_yearly_budget = 0;
                                $totalfirstTrimBudget = 0;
                                $totalsecondTrimBudget= 0;
                                $totalthirdTrimBudget = 0;
                                $totalgon = 0;
                                $totalgrant = 0;

                                $total_programs = 0;
                                ?>
                                @foreach($implementingOffice as $implementing_office)
                                    <?php $total_programs += $implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->count() ?>
                                    <tr>

                                        <td>{{$implementing_office->name}}</td>
                                        <td>{{ $implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->get()->count()}}</td>
                                        <td>
                                            <?php
                                            $total_budget = 0;
                                            foreach($implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->get() as $project){
                                                if(count($project->allocation)>0){
                                                    $total_budget += $project->allocation->sortByDesc('id')->first()->total_budget;
                                                }
                                            }
                                            $total_yearly_budget+= $total_budget;
                                            ?>
                                            {{number_format($total_budget)}}
                                        </td>
                                        <td>
                                            <?php
                                            $firstTrimBudget = 0;
                                            foreach($implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->get() as $project){
                                                if(count($project->allocation)>0){
                                                    $firstTrimBudget += $project->allocation->sortByDesc('id')->first()->first_trim;
                                                }
                                            }
                                            $totalfirstTrimBudget+= $firstTrimBudget;
                                            ?>
                                            {{number_format($firstTrimBudget)}}
                                        </td>
                                        <td>
                                            <?php
                                            $secondTrimBudget = 0;
                                            foreach($implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->get() as $project){
                                                if(count($project->allocation)>0){
                                                    $secondTrimBudget += $project->allocation->sortByDesc('id')->first()->second_trim;
                                                }
                                            }
                                            $totalsecondTrimBudget+= $secondTrimBudget;
                                            ?>
                                            {{number_format($secondTrimBudget)}}
                                        </td>
                                        <td>
                                            <?php
                                            $thirdTrimBudget = 0;
                                            foreach($implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->get() as $project){
                                                if(count($project->allocation)>0){
                                                    $thirdTrimBudget += $project->allocation->sortByDesc('id')->first()->third_trim;
                                                }
                                            }
                                            $totalthirdTrimBudget+= $thirdTrimBudget;
                                            ?>
                                            {{number_format($thirdTrimBudget)}}
                                        </td>
                                        <td>
                                            <?php
                                            $gon = 0;
                                            foreach($implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->get() as $project){
                                                if(count($project->allocation)>0){
                                                    $gon += $project->allocation->sortByDesc('id')->first()->gon;
                                                }
                                            }
                                            $totalgon+= $gon;
                                            ?>
                                            {{number_format($gon)}}

                                        </td>
                                        <td>
                                            <?php
                                            $grant = 0;
                                            foreach($implementing_office->projects()->where('show_on_running','1')->whereIn('monitoring_office_id',$monitoring_office)->where('budget_topic_id',$budgetTopicId)->get() as $project){
                                                if(count($project->allocation)>0){
                                                    $grant += $project->allocation->sortByDesc('id')->first()->grants;
                                                }
                                            }
                                            $totalgrant+= $grant;
                                            ?>
                                            {{number_format($grant)}}

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>कूल</th>
                                    <th>{{$total_programs}}</th>
                                    <th>{{number_format($total_yearly_budget)}}</th>
                                    <th>{{ number_format($totalfirstTrimBudget )}}</th>
                                    <th>{{ number_format($totalsecondTrimBudget )}}</th>
                                    <th>{{ number_format($totalthirdTrimBudget )}}</th>
                                    <th>{{ number_format($totalgon )}}</th>
                                    <th>{{ number_format($totalgrant )}}</th>

                                </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-footer">
                        <button id="btnExport" class="btn btn-success" data-loading-text="Exporting..." autocomplete="off">Export Report</button>
                        <button id="btnPrint" class="btn btn-success" data-loading-text="Printing..." autocomplete="off">Print Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/jquery.table2excel.js')}}"></script>
    <script src="{{ asset('public/js/jquery.printElement.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#btnExport').click(function(){
                $("#dvData").table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "myFileName",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });
            })
            $('#btnPrint').click(function(){
                $('#dvData').show().printElement();
            })
        });
    </script>
@stop