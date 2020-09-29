@extends('layouts.admin_layout')
@section('headerContent')
@stop
@section('content')
<link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <h1 class="text-center">Sick Projects</h1>
        <div class="col-md-12">
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>S.No</th>
                        <th>Division Name</th>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th>Base Year</th>
                        <th>Remarks</th>
                        <th>Completed Fiscal Year</th>
                    </tr>

                    @foreach($projects as $index=>$project)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $project->implementing_office->name }}</td>
                            <td>{{ $project->project_code}}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->fiscal_year?$project->fiscal_year->fy:"N/A"}}</td>
                            <td>{{ $project->lastProgress?$project->lastProgress->project_remarks:"N/A" }}</td>
                            <td>{{ $project->completedFiscalYear?$project->completedFiscalYear->fy:"N/A" }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@stop
