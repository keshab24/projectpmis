@extends('layouts.admin_layout')
@section('headerContent')
@stop
@section('content')
    <link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <h1 class="text-center">Projects with @if($attribute==4) Time Extension @else {{ variations_choose()[$attribute]  }} @endif</h1>
            <div class="col-md-12">
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>S.No</th>
                            <th>Division Name</th>
                            <th>Division Code</th>
                            <th>Project Code</th>
                            <th>Project Name</th>
                            <th>Approved Date</th>
                            @if($attribute==4)
                                <th>End Date</th>
                            @endif
                            <th>Number Of Times</th>
                            <th>Fiscal Year</th>
                            @if($attribute==4)
                                <th>Liquidated Damage</th>
                            @endif
                        </tr>

                        @foreach($projects as $index=>$project)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $project->implementing_office->name }}</td>
                                <td>{{ $project->implementing_office->office_code }}</td>
                                <td>{{ $project->project_code}}</td>
                                <td>{{ $project->name }}</td>
                                <td>
                                    @if($attribute==4)
                                        <?php
                                        $result=$project->timeExtension()->orderBy('extended_on','desc')->first()->extended_on;
                                        ?>
                                    @else
                                        <?php
                                        $result= $project->variation()->where('status',$attribute)->orderBy('vope_date','desc')->first()->vope_date;
                                        ?>
                                    @endif
                                    {{ $result }}
                                </td>
                                @if($attribute==4)
                                    <td>
                                        <?php
                                        $result=$project->timeExtension()->orderBy('extended_on','desc')->first()->end_date;
                                        ?>
                                        {{ $result }}
                                    </td>
                                @endif
                                <td>
                                    @if($attribute==4)
                                        {{$project->timeExtension()->count()}}
                                    @else
                                        {{  $project->variation()->where('status',$attribute)->count()}}
                                    @endif
                                </td>
                                <td>
                                    @if($attribute==4)
                                        <?php
                                        $result=$project->timeExtension()->orderBy('extended_on','desc')->first()->extended_on;
                                        ?>
                                    @else
                                        <?php
                                        $result= $project->variation()->where('status',$attribute)->orderBy('vope_date','desc')->first()->vope_date;
                                        ?>
                                    @endif
                                    {{ $result!=''?getFiscalyearFromDate($result):"" }}

                                </td>
                                @if($attribute==4)
                                    <td>{{ $project->timeExtension->last()->liquidated_damage==1?"Y":"N" }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
