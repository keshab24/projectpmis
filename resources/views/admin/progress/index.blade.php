@extends('layouts.admin_layout')


@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/lightbox/css/lightbox.css')}}" type="text/css"/>
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li class="active"><a href="{{route('progress.index')}}"><span
                                    class="glyphicon glyphicon-blackboard"></span> Progress (Project)</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        {!! Form::open(['route'=>'progress.index','method'=>'get']) !!}
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            @if(isset($_GET['search']))
                                @if($projects->first())
                                    <a href="{{route('project.index')}}/search?search={{$_GET['search']}}"
                                       class="btn btn-warning showToolTip" title="Go Back" role="button"><span
                                                class="glyphicon glyphicon-chevron-left"></span> Go Back</a>
                                @endif
                            @endif
                            <a href="{{route('progress.create')}}" class="btn btn-primary showToolTip"
                               title="Add  Progresses" role="button"><span class="glyphicon glyphicon-plus"></span> Add
                                Progresses</a>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="input-group">
                                {!! Form::text('search',$default_search,['placeholder'=>'Search...','class'=>'form-control firstInput']) !!}
                                <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><span
                                            class="glyphicon glyphicon-filter"></span>  <span
                                            class="hidden-xs hidden-sm">Filter!</span></button>
                            </span>
                            </div><!-- /input-group -->
                        </div>
                        {!! Form::close() !!}
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            {{--<a href="{{route('progress.index')}}?trashes=yes" class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>--}}
                            <a href="{{route('progress.index')}}?completed=yes"
                               class="btn btn-info showToolTip pull-right" title="Completed" role="button"><span
                                        class="fa fa-thumbs-o-up"></span> <span class="badge"></span></a>
                            <a href="{{route('progress.index')}}?handover=yes"
                               class="btn btn-info showToolTip pull-right" title="Hand Over" role="button"><span
                                        class="fa fa-thumbs-o-up"></span> <span class="badge"></span></a>
                            {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','Project') !!}
                        </div>
                        <div class="clearfix"></div>
                        <p> Showing {{ $projects->firstItem() }}
                            to {{ $projects->lastItem() }} of {{ $projects->total() }} entries </p>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th width="15%">
                                        Project Code
                                    </th>
                                    <th width="15%">
                                        Total Allocated Budget
                                    </th>

                                    <th width="40%">
                                        अायाेजनाहरू
                                    </th>

                                    <th width="15%">
                                        प्रगति
                                    </th>

                                    <th>
                                        <a href="{{route('progress.index').'?orderBy=budget_topic_num&order='.$order.$other_data}}"
                                           class="sortText showToolTip" title="Sort By Created Date">हालको अवस्था</a>
                                    </th>
                                </tr>
                                @if($projects->isEmpty())
                                    <tr>
                                        <td colspan="12">
                                            <div class="alert alert-warning" role="alert">Please add some projects
                                                first!!
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <?php $i = 1; ?>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>
                                                <span>{!! $project->project_code !!}
                                                    <br>
                                                    <button type="button" class="btn btn-danger btn-xs"
                                                            data-toggle="modal" id="trigger_{{$project->id}}"
                                                            data-pid="{{$project->id}}"
                                                            data-target="#handover_{{$project->id}}">Fill HO Date</button>

                                                    <div class="modal fade" data-pid="{{$project->id}}"
                                                         id="handover_{{$project->id}}" tabindex="-1" role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <br>
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close"><span
                                                                                            aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title">Handover Detail</h4>
                                                                            </div>
                                                                            <div class="modal-body">
<strong style="color: #F00;">{{ $project->name }}</strong>
                                                                                <form action="">
                                                                                        Project Fiscal Year : {{ $project->fiscal_year->fy }}
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-6">
                                                                                            <label for="hod">Hand Over Date</label>
                                                                                            <input type="text"
                                                                                                   name="hod"
                                                                                                   id="hod_{{$project->id}}"
                                                                                                   class="form-control nepaliDate"
                                                                                                   placeholder="Choose Handover Date"
                                                                                                   @if ($project->ho_date && $project->ho_date != '0000-00-00')
                                                                                                   value= {{ $project->ho_date }}
                                                                                                    @endif
                                                                                            />
                                                                                        </div>
                                                                                        <div class="form-group col-md-6">
                                                                                            <label for="wcd">Work Complete Date</label>
                                                                                            <input type="text"
                                                                                                   name="wcd"
                                                                                                   id="wcd_{{$project->id}}"
                                                                                                   class="form-control nepaliDate"
                                                                                                   placeholder="Choose Work complete Date"
                                                                                                   @if ($project->completed_date && $project->completed_date != '0000-00-00')
                                                                                                   value= {{ $project->completed_date }}
                                                                                                    @endif
                                                                                            />
                                                                                        </div>
                                                                                    </div>
                                                                                </form>

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                        class="btn btn-success"
                                                                                        data-dismiss="modal">No</button>
                                                                                <button data-pid="{{$project->id}}"
                                                                                        type="button"
                                                                                        class="btn btn-danger handover-p">Yes, handover this project!</button>
                                                                            </div>
                                                                        </div><!-- /.modal-content -->
                                                                    </div><!-- /.modal-dialog -->
                                                                </div>
                                                            </div>
                                                    @if($project->progresses()->where('fy_id','<',intval(session()->get('pro_fiscal_year')))->orderBy('id','desc')->get() && $project->ho_date == null && $project->project_status  != 2 && $project->ho_date != "0000-00-00")
                                                        @foreach($project->progresses()->where('fy_id','<',intval(session()->get('pro_fiscal_year')))->orderBy('id','desc')->get() as $index=>$progress)
                                                            <?php $remarks[$index] = $progress->project_remarks ?>
                                                        @endforeach
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <?php

                                                    $allocation = $project->allocation()->where('fy_id', session()->get('pro_fiscal_year'))->orderBy('amendment','desc')->first();
                                                ?>
                                            @if($allocation)
                                                        {{ $allocation->total_budget ?? ''}}
                                            @endif
                                            </td>
                                            <td>{!! $project->name !!}</td>
                                            <td>
                                                @if($project->progresses->count()>0)
                                                    <?php
                                                    $remarks = array();
                                                    ?>
                                                    @foreach($project->progresses()->where('fy_id',intval(session()->get('pro_fiscal_year')))->orderBy('id','desc')->get() as $index=>$progress)

                                                        <a class="btn btn-success btn-xs" href="#"
                                                           title="{{$progress->project_remarks}}">
                                                            {{$progress->month->name}}
                                                            <?php $remarks[$index] = $progress->project_remarks ?>
                                                        </a>
                                                        <a class="btn btn-xs btn-warning"
                                                           title="{{$progress->month->name}} को प्रगति सम्पादन गर्नुहोस"
                                                           href="{{ route('progress.edit',$progress->id) }}"><span
                                                                    class="fa fa-pencil"></span></a>
                                                    @endforeach
                                                @else
                                                    No Progress submitted yet
                                                @endif
                                            </td>
                                            <td>
                                                @if($project->progresses->count()>0)
                                                    @foreach($remarks as $remark)
                                                        <p>{{ $remark }}</p>
                                                    @endforeach
                                                @else
                                                    <span style="color:indianred;">हालसम्म कुनै प्रगति प्रबिस्ट नगरिएको</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>

                            @if(isset($not_found))
                                <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                            @endif
                            <div class="text-center">
                                <p> Showing {{ $projects->firstItem() }}
                                    to {{ $projects->lastItem() }} of {{ $projects->total() }} entries </p>
                                {!! str_replace('/?', '?', $projects->appends(Request::input())->render()) !!}
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                                {!! massAction('','local','Project') !!}
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/lightbox/js/lightbox.js')}}"></script>
    <script>
        $(".handover-p").click(function () {
            var project_id = $(this).attr('data-pid');
            var control = $(this);
            var hod = $('#hod_' + project_id).val();
            var wcd = $('#wcd_' + project_id).val();
            var $hod = $('#hod_' + project_id);
            if (project_id > 0) {
                if (hod.length >= 8) {
                    $.ajax({
                        url: "{{ route('project.handover') }}",
                        data: {hod: hod, wcd: wcd, project_id: project_id, _token: "{{ csrf_token() }}"},
                        type: "post",
                        success: function (response) {
//                            $("#trigger_" + project_id).hide();
                            $("#handover_" + project_id).fadeOut('fast');
//                            alert("Project has been handover successfully!");
                        },
                        beforeSend: function () {
                            control.html("Please Wait...");
                            control.removeClass('btn-danger');
                            control.addClass('btn-info');
                        }
                    });
                } else {
                    $hod.focus();
                }
            } else {
                $("#trigger_" + project_id).hide();
                $("#handover_" + project_id).fadeOut('fast');
            }
        });

        $('.nepaliDate').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10 // Options | Number of years to show
        });
    </script>
@stop