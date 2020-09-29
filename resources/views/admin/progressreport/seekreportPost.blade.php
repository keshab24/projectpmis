@extends('layouts.admin_layout')
@section('headerContent')
<style>
    .extension-count{
        background-color: #ffa50061!important;
    }
    .both-condition{
        background-color: #fb8c8c54!important;
    }
    .time-period{
        background-color: #a3d3ef26!important;
    }
</style>
@stop
@section('content')
<link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="text-center">Projects</h1>
            
        </div>
        <div class="col-sm-4">
            <div class="pull-right">
                <br>
                <p>Color Codes ---- 
                    Both Condition : <span class="both-condition" style="border:1px solid #654d4d">&nbsp;&nbsp;&nbsp;</span>
                    Time Period : <span class="time-period" style="border:1px solid #654d4d">&nbsp;&nbsp;&nbsp;</span>
                    Extension Count : <span class="extension-count" style="border:1px solid #654d4d">&nbsp;&nbsp;&nbsp;</span>
                </p>
                <div class="strong">
                    @if(isset($project_period_filter))
                    म्याद थप भएको अवधि : {{ $project_period_filter * 100 }}%  or more
                    @endif
                    <span class="pull-right">
                        @if(isset($project_time_ext_count))
                            म्याद थप भएको : {{ $project_time_ext_count }} times or more
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <table class="table">
                    <tr>
                        <th width="3%">S.No</th>
                        <th width="15%">Division Name</th>
                        <th width="8%">Project Code</th>
                        <th width="20%">Project Name</th>
                        <th width=8%>Base Year</th>
                        <th>Remarks</th>
                        <th width="8%">Project Period (days)</th>
                        <th widt=8%>No of EOT</th>
                        <th widt=8%>Extension Period(days)</th>
                    </tr>
                    <?php $index = 0; ?>
                    @foreach($projects as $project)
                        <?php 
                            $setting = $project->projectSettings->where('fy',session()->get('pro_fiscal_year'))->first();
                            if(!$setting){
                                $setting = $project;
                            }
                            $show_project = false;
                            $time_period_limit = false;
                            $time_extension_count_limit = false;
                            $both_condition = false;
                            
                            $contract_date = $project->procurement->contract_date_eng;
                            $completion_date = $project->procurement->completion_date_eng;
                            $project_period = ((strtotime($completion_date) - strtotime($contract_date)) / (60 * 60 * 24)) + 1;
                            if($project_period == 0){
                                //contract or completion date not proper
                                //put 1 for operation purpose - 
                                $project_period = 1;
                            }
                            
                            $date_today = date('Y-m-d');
                            $extension_period = ((strtotime($date_today) - strtotime($completion_date)) / (60 * 60 * 24))+1;
                            $extension_ratio = $extension_period / $project_period;
                      
                            if(isset($project_period_filter)){
                                if($extension_ratio >= $project_period_filter)
                                    $time_period_limit = true;
                                else
                                    $time_period_limit = false;
                            }
                            
                            if(isset($project_time_ext_count) && $project_time_ext_count != 'not_extended'){
                                if($project->time_extension_count >= $project_time_ext_count)
                                    $time_extension_count_limit = true;
                                else
                                    $time_extension_count_limit = false;
                            }

                            if(!$both_condition){
                                $both_condition = $time_extension_count_limit && $time_period_limit;
                            }
                            
                            $show_project = $time_period_limit || $time_extension_count_limit;
                            if((!isset($project_period_filter) && !isset($project_time_ext_count)) || (isset($project_time_ext_count) && $project_time_ext_count == 'not_extended')){
                                $both_condition = false;
                            }
                            
                            if((!isset($project_time_ext_count) && !isset($project_period_filter)) || (isset($project_time_ext_count) && $project_time_ext_count == 'not_extended')){
                                $show_project = true;
                            }
                            if(!$project->procurement->completion_date || $project->procurement->completion_date == '0000-00-00'){
                                $show_project = false;
                            }

                        ?>
                        @if(isset($show_project) && $show_project)
                            <tr class="{{ ($both_condition) ? 'both-condition' : (($time_period_limit == 1 && $time_extension_count_limit == 0) ? 'time-period':(($time_period_limit == 0 && $time_extension_count_limit == 1) ? 'extension-count' : ''))}}">
                            <td>{{ $index+1 }}</td>
                                <td>{{ $setting->implementing_office->title }}</td>
                                <td>{{ $setting->project_code}}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->fiscal_year?$project->fiscal_year->fy:"N/A"}}</td>
                                <td>{{ $project->lastProgressWithoutLimit?$project->lastProgressWithoutLimit->project_remarks:"N/A" }}</td>
                                <td>{{ $project_period }}</td>
                                <td>{{ $project->time_extension_count }}</td>
                            <td>
                                {{ $extension_period }} @if($extension_period && isset($extension_ratio))({{ number_format(($extension_ratio * 100),0) }} %)@endif
                            </td>
                            </tr>
                            <?php $index++; ?>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@stop
