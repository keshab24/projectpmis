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
                <div class="text-center">
                    <h2>म्याद नाघी सकेका, म्याद थप गर्न पर्ने</h2>
                    @if(isset($implementing_office_name))
                        <h3>
                        {{ $implementing_office_name }}
                        </h3>
                    @endif
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>
                                कोड
                            </th>
                            <th>
                                आयोजनाको नाम
                            </th>
                            @if(!isset($implementing_office_name))
                                <th>
                                    डिभिजन
                                </th>
                            @endif
                            <th>
                                मिती
                            </th>
                        </tr>
                    @foreach($extended as $project)
                        <tr>
                            <td>
                                {{ $project->project_code }}
                            </td>
                            <td>
                                {{ $project->name }}
                            </td>
                            @if(!isset($implementing_office_name))
                                <td>
                                    {{ $project->implementing_office->name }}
                                </td>
                            @endif
                            <td>
                                {{ count($project->timeExtension) > 0? $project->timeExtension->last()->end_date:$project->procurement->completion_date}}
                            </td>
                        </tr>
                    @endforeach
                    </table>
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