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
                    <h2>{{ $project->name }}</h2>
                    <h3>{{ $project->fiscal_year->fy }}</h3>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>
                                Index
                            </th>
                            <th>
                                Fiscal Year
                            </th>
                            <th>
                                Bill Expenditure
                            </th>
                            <th>
                                Remarks
                            </th>
                        </tr>
                        @foreach($project->progresses as $index=>$progress)
                            <tr>
                                <td>
                                    {{ $index+1 }}
                                </td>
                                <td>
                                    {{ $progress->fy->fy }}
                                </td>
                                <td>
                                    {{ $progress->bill_exp }}
                                </td>
                                <td>
                                    {{ $progress->project_remarks }}
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