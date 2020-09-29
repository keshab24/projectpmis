@extends('layouts.admin_layout')
@section('headerContent')
@stop
@section('content')
    <link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <h1 class="text-center">Project and Payment Status</h1>
            <div class="col-md-12">
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th width="10%">Division Name</th>
                            <th>Project Name</th>
                            <th>Project Code</th>
                            <th>Progress Status</th>
                            <th>Payment Status</th>

                        </tr>
                        <?php
                        $grand = 0;
                        ?>
                        @foreach($implementingoffices as $index=>$implementingoffice)
                            <?php
                            $arr = collect();
                            $count = 0;
                            $project = $implementingoffice->projectSetting()->where('budget_id',$budget_topic_id)->where('monitoring_id',Auth::user()->implementingOffice->id)->join('pro_projects','pro_projects.id', '=','pro_project_settings.project_id' )->where('show_on_running','1');
                            if ($project_status == 0) {
                                $project = $project->where('project_status', 0);
                            } else {
                                $project = $project->where('project_status', '!=', 0);
                            }
                            if ($fiscalyear != 0) {
                                $project = $project->where('start_fy_id', $fiscalyear);
                            }
                            ?>
                            <tr>
                                <td colspan="5"><strong>{{ $implementingoffice->name }}</strong>
                                    @if(count($implementingoffice->child))
                                        <table class="table table-striped table-hover">
                                            @foreach($implementingoffice->child as $child_index => $child_implementing_office)
                                                <?php
                                                $child_projects = $child_implementing_office->projectSetting()->where('budget_id',$budget_topic_id)->where('monitoring_id',Auth::user()->implementingOffice->id)->join('pro_projects','pro_projects.id', '=','pro_project_settings.project_id' )->where('show_on_running','1');
                                                if ($project_status == 0) {
                                                    $child_projects = $child_projects->where('project_status', 0);
                                                } else {
                                                    $child_projects = $child_projects->where('project_status', '!=', 0);
                                                }
                                                if ($fiscalyear != 0) {
                                                    $child_projects = $child_projects->where('start_fy_id', $fiscalyear);
                                                }
                                                if ($arr->isEmpty()) {
                                                    $arr = $child_projects->get();
                                                } else {
                                                    $arr = $arr->merge($child_projects->get());
                                                }
                                                ?>

                                            @endforeach
                                        </table>
                                    @endif
                                </td>
                            </tr>
                            @if(isset($arr))
                                <?php $office_name = ''; ?>
                                @foreach($arr as $i => $child_project)
                                    <tr>
                                        <td><strong>
                                                @if($office_name!=optional($child_project->implementing_office)->name)
                                                    &nbsp;&nbsp;&nbsp;&nbsp; ===> {{ optional($child_project->implementing_office)->name }}
                                                    <?php
                                                    $office_name = optional($child_project->implementing_office)->name;
                                                    ?>
                                                @endif
                                            </strong>
                                        </td>
                                        <td width="40%">{{ $child_project->name }}</td>
                                        <td>{{ $child_project->project_code }}</td>
                                        <td>{{ getProjectStatus()[$child_project->project_status] }}</td>
                                        <td>{{ $child_project->payment_status?getPaymentStatus()[$child_project->payment_status]:'-' }}</td>
                                    </tr>
                                    <?php $grand++ ?>
                                @endforeach
                            @endif
                            @foreach($project->get() as $project)
                                <tr>
                                    <td></td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->project_code }}</td>
                                    <td>{{ getProjectStatus()[$project->project_status] }}</td>
                                    <td>{{ $project->payment_status?getPaymentStatus()[$project->payment_status]:'-' }}</td>
                                </tr>

                                <?php
                                    $grand++

                                    ?>
                            @endforeach
                        @endforeach
                        <tr>
                            <td><strong>Total Project Count : {{$grand}}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
