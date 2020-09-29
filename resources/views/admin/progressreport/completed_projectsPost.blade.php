@extends('layouts.admin_layout')
@section('headerContent')
@stop
@section('content')
<link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <h1 class="text-center">Completed Count</h1>
        <div class="col-md-12">
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>S.No</th>
                        <th>Division Name</th>
                        <th>Count</th>

                    </tr>
                    <?php


                        $grand=0;

                    ?>
                    @foreach($implementingoffices as $index=>$implementingoffice)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>
                                {{ $implementingoffice->name }}
                                <?php $child_office_projects_count =0;?>
                                @if(count($implementingoffice->child))
                                    <br><br>
                                    <table class="table table-hover">
                                        @foreach($implementingoffice->child as $child_index => $child_implementing_office)
                                            <tr>
                                                <td>{{ $child_index+1 }}</td>
                                                <td>{{ $child_implementing_office->getOriginal()['name'] }}</td>
                                                <?php
                                                $count =0;
                                                $project=$child_implementing_office->projectSetting()->where('budget_id',$budget_topic_id)->where('monitoring_id',Auth::user()->implementingOffice->id)->join('pro_projects','pro_projects.id', '=','pro_project_settings.project_id' )->where('show_on_running','1');
                                                if($project_status==0){
                                                    $project=$project->where('project_status',0);
                                                }else{
                                                    $project=$project->where('project_status','!=',0);
                                                }
                                                if($fiscalyear!=0){
                                                    $project=$project->where('start_fy_id',$fiscalyear);
                                                }
                                                foreach($project->get() as $project){
                                                    $count++;
                                                    $grand++;
                                                    $child_office_projects_count++;
                                                }
                                                ?>
                                                <td class="text-right">{{ $count }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                                <?php
                                $count =0;
                                $project=$implementingoffice->projectSetting()->where('budget_id',$budget_topic_id)->where('monitoring_id',Auth::user()->implementingOffice->id)->join('pro_projects','pro_projects.id', '=','pro_project_settings.project_id' )->where('show_on_running','1');
                                if($project_status==0){
                                    $project=$project->where('project_status',0);
                                }else{
                                    $project=$project->where('project_status','!=',0);
                                }
                                if($fiscalyear!=0){
                                    $project=$project->where('start_fy_id',$fiscalyear);
                                }
                                foreach($project->get() as $project){
                                    $count++;
                                    $grand++;
                                }
                                ?>
                            <td>{{ $count + $child_office_projects_count }}</td>
                        </tr>


                    @endforeach
                    <tr>
                        <td colspan="2"></td>
                        <td> {{ $grand }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
