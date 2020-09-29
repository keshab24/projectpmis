<?php
 $implementing_office_id=Request::get('implementing_office_id');
 $budget_topic_id=Request::get('budget_topic_id');
 $status=Request::get('status');
 $storey_area=Request::get('storey_area');
$params='?budget_topic_id='.$budget_topic_id.'&implementing_office_id='.$implementing_office_id.'&status='.$status.'&storey_area='.$storey_area.'&excel=1';
?>
@extends('layouts.admin_layout')
@section('headerContent')
@stop
@section('content')
    <div class="container">
        <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
            <div class="row">
                <h1 class="text-center">{{ getStoreyArea()[$storey ]}}</h1>
                <a href="{{ route('building.report.view') }}{{$params}}" class="btn btn-info pull-right">Export</a>
                <div class="clearfix"></div>
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                    </tr>
                    @forelse($projects as $project)
                        <tr>
                            <td>
                                {{ $project->name }}
                            </td>
                            <td>
                                {{ $project->project_code }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12">
                                <div class="alert alert-warning" role="alert">Please add some projects first!!</div>
                            </td>
                        </tr>
                    @endforelse
                </table>
                {!! str_replace('/?', '?', $projects->appends(Request::input())->render()) !!}
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