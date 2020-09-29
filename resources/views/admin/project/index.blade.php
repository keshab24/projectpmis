@extends('layouts.admin_layout')


@section('headerContent')
<link rel="stylesheet" href="{{asset('public/admin/plugin/lightbox/css/lightbox.css')}}" type="text/css" />
@stop
@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                <li class="active"><a href="{{route('project.index')}}"><span class="glyphicon glyphicon-blackboard"></span> Project</a></li>
            </ol>
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="col-md-12 col-lg-12">
                    <div class="">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>क्र. स.</th>
                                <th>करान्वयन निकाय</th>
                                <th width="40%">
                                    अायाेजनाहरू / कार्यक्रमहरु
                                </th>
                                <th>
                                    Project Code
                                </th>
                                <th>
                                    LMBIS Code
                                </th>
                                <th width="10%">
                                    <a href="{{route('project.index').'?orderBy=updated_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>
                                </th>
                                <th width="3%">
                                    <a href="{{route('project.index').'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Status</a>
                                </th>
                                <th width="10%">
                                    <a href="{{route('project.index').'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Action</a>
                                </th>
                            </tr>
                                    <?php $i=1; $implementing_office_id=0;$color='info'; $serial=1;
                                        if(!isset($_GET['page'])){
                                            $_GET['page']=1;
                                        }
                                            $serial=$serial+(($_GET['page']-1)*50);
                                    ?>
                                    @forelse($projects as $project)

                                        @if($project->implementing_office_id!=$implementing_office_id)
                                            <?php
                                            $color = $color == 'success'?'info':'success';
                                            ?>
                                        @endif
                                        @php
                                            $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
                                            if(!$setting){
                                                $setting = $project;
                                            }
                                        @endphp
                                    <tr class="{{$color}}">
                                        <td>
                                            {{ $serial++ }}
                                        </td>
                                        <td>
                                            @if($setting->implementing_office->id!=$implementing_office_id)
                                                <?php
                                                $implementing_office_id = $setting->implementing_office->id;
                                                ?>
                                                    @if($setting->implementing_office->level == 2) <small>{{$setting->implementing_office->parent->title}}
                                                        ></small><br>@endif
                                                    @if($setting->implementing_office->level == 3) <small>{{$setting->implementing_office->parent->parent->title}}
                                                        >{{$project->implementing_office->parent->title}}
                                                        ></small><br>@endif
                                                    @if($setting->implementing_office->level == 4) <small>{{$setting->implementing_office->parent->parent->parent->title}}>{{$setting->implementing_office->parent->parent->title}}
                                                        >{{$setting->implementing_office->parent->title}}
                                                        ></small><br>@endif
                                                <strong>{{$setting->implementing_office->title}}</strong>
                                            @endif
                                        </td>
                                        <td>{!! $project->name !!}</td>
                                        <td>{!! $setting->project_code !!}</td>
                                        <td class="{{ !isset($_GET['trashes']) ? 'magic-update' : '' }}"
                                            data-id="{{$project->id}}" data-model="Project"
                                            data-field="lmbis_code" title="Click to Edit">{{ $project->lmbis_code }}</td>
                                        <td>{!! $project->updated_at !!}</td>
{{--                                        <td>status</td>--}}
                                        <td>
                                            <?php
                                                $button='success';
                                                $text='Running';
                                                if($project->show_on_running!=1){
                                                    $button='danger';
                                                    $text='In-Active';
                                                }
                                                if($project->cancelled_reason == 3){
                                                    $button='warning';
                                                    $text='Project Dropped';
                                                }

                                            ?>
                                                @php
                                                    $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
                                                    if(!$setting){
                                                        $setting = $project;
                                                    }
                                                @endphp
                                            <a title="Summary Report" href=""><span class="label label-{{ $button }}">{{ $text }}</span></a>
                                                @if($project->revisedFrom)
                                                    <a title="Summary Report" href=""><span class="label label-danger"> Terminated </span></a>
                                                @endif

                                                @if($project->cancelled_reason == 3)
                                                    <br>
                                                <small>
                                                    {{ $project->cancelled_remarks }}
                                                </small>
                                                @endif

                                        </td>
                                        <td>
                                            @if(!isset($_GET['trashes']))
                                                @if(Auth::User()->type_flag !== 5)
                                                    <a class="btn btn-xs btn-warning" title="Project Edit" href="{{ route('project.edit',$project->id) }}"><span class="fa fa-pencil"></span></a>
                                                    <a class="btn btn-xs btn-info" title="Project Detail" href="{{route('project.show',$project->id)}}"><span class="fa fa-search"></span></a>
                                                @endif

                                                @if(Auth::User()->implementingOffice->isMOnitoring==1 && $project->monitoringOffice->id!=341)
                                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                                    {!! delete_form(['project.destroy',$project->id], 'pro_my_form'.$i++) !!}
                                                @endif

                                                    @if($project->payment_status == 1 && $project->completed_date && (Auth::User()->access=="Top Level" || Auth::User()->access=="Root Level") && optional(Auth::User()->implementingOffice)->isMonitoring)
                                                    <a type="" class="btn {{ isset($_GET['completed'])?"btn-success":"btn-danger" }} btn-xs showToolTip confirmButtonWithInput" title="{{ isset($_GET['completed'])?"Add To Running List":"Remove From Running List" }}" data-toggle="modal" data-target="#finishAProject" data-form-id="pro_my_form_completed{{$project->id}}"><span class="fa fa-hourglass-end"></span></a>{!! $project->show_on_running ? completed_form(['project_status_complete'],$setting)  : '' !!}
                                                @endif

                                                @if(Auth::User()->implementing_office_id === 410)
                                                    <a class="btn btn-info btn-xs showToolTip" title="Insert daily Progress" href="{{ route('daily.progress', $project->id).'?today' }}"><i class="fa fa-bar-chart"></i></a>
                                                    <a class="btn btn-primary btn-xs showToolTip" title="View daily Progress" href="{{ route('report.daily.progress', $project->id).'?today' }}"><i class="fa fa-area-chart"></i></a>
                                                @endif
                                            @else
                                                @if(Auth::User()->type_flag !== 5)
                                                    <a href="{{route('restore',['Project',$project->id])}}" class="btn btn-xs btn-success showToolTip" title="Restore" role="button"><span class="fa fa-reply"></span></a>&nbsp;
                                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Permanent Delete" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="fa fa-times-circle"></span></a>&nbsp;
                                                    {!! hard_delete_form(['project.destroy',$project->id], 'pro_my_form'.$i++) !!}
                                                @endif
                                            @endif
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
                        @if(isset($not_found))
                        <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                        @endif
                        <p class="text-center"> Showing {{ $projects->firstItem() }}
                            to {{ $projects->lastItem() }} of {{ $projects->total() }} entries </p>
                        <div class="pull-right">
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
<script type="text/javascript" src="{{asset('public/admin/js/editor.js')}}"></script>
<script>

    $('.confirmButtonWithInput').click(function (e) {
        e.preventDefault()
        finishAProject(this)
    })
</script>
@stop