@extends('layouts.admin_layout')
@section('headerContent')
@endsection
@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h2>Work Completed in FY {{ $fiscalYear }}</h2>
            </div>
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Division Name</th>
                        <th>Project Code</th>
                        <th>Name</th>
                        <th>Project Status</th>
                        <th>Payment Status</th>
                    </tr>
                    @foreach($projects as $project)
                        <tr>
                            <td>{{ $project->implementing_office->name }}</td>
                            <td>{{ $project->project_code }}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ getProjectStatus()[$project->project_status ]}}</td>
                            <td>{{ getPaymentStatus()[$project->payment_status ]}}</td>
                        </tr>
                    @endforeach
                    <tr>
                       <th>
                           Total
                       </th>
                        <th  colspan="4">
                            {{ $count }}
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection