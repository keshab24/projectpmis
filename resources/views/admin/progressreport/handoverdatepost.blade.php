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
                    <h2>हस्तान्तरण भएका, हस्तान्तरण मिति नभएका</h2>
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
                            @if(!isset($implementing_office_name))
                                <th>
                                    डिभिजन
                                </th>
                            @endif
                            <th>
                                आयोजनाको नाम
                            </th>
                        </tr>
                        @foreach($projects as $project)
                            <tr>
                                <td>
                                    {{ $project->project_code }}
                                </td>
                                @if(!isset($implementing_office_name))
                                    <td>
                                        {{ $project->implementing_office->name }}
                                    </td>
                                @endif
                                <td>
                                    {{ $project->name }}
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