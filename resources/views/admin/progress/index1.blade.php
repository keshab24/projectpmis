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
                    <li class="active"><a href="{{route('progress.index')}}"><span class="glyphicon glyphicon-blackboard"></span> Project</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        {!! Form::open(['route'=>'progress.index','method'=>'get']) !!}
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="{{route('progress.create')}}" class="btn btn-primary showToolTip pull-left" title="Add  Progresses" role="button"><span class="glyphicon glyphicon-plus"></span> Add Progresses</a>
                            <a href="{{route('progress.edit')}}" class="btn btn-warning showToolTip pull-left" title="Update Progresses" role="button"><span class="glyphicon glyphicon-edit"></span> Update Progresses</a>

                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="input-group">
                                {!! Form::text('search',$default_search,['placeholder'=>'Search Budget Topic...','class'=>'form-control firstInput']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                            </span>
                            </div><!-- /input-group -->
                        </div>
                        {!! Form::close() !!}
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <a href="{{route('progress.index')}}?trashes=yes" class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                            {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','Project') !!}
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th width="5%">
                                        <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select/Deselect all items">
                                            <input type="checkbox" name="select_all" id="select_all" class="sixth_color" />
                                            <label for="select_all"></label>
                                        </div>
                                    </th>

                                    <th>
                                        अायाेजनाहरू
                                    </th>

                                    <th>
                                        प्रगति
                                    </th>

                                    <th>
                                        <a href="{{route('progress.index').'?orderBy=budget_topic_num&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Created Date">हालको अवस्था</a>
                                    </th>
                                    <th width="5%">
                                        <a href="{{route('progress.index').'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Status</a>
                                    </th>
                                    <th width="13%">Action</th>
                                </tr>
                                @if($projects->isEmpty())
                                    <tr>
                                        <td colspan="12">
                                            <div class="alert alert-warning" role="alert">Please add some projects first!!</div>
                                        </td>
                                    </tr>
                                @else
                                    <?php $i=1; ?>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>
                                                <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                                    <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$project->id}}" />
                                                    <label for="select_item{{$i}}"></label>
                                                </div>
                                            </td>
                                            <td>{!! $project->name !!}</td>
                                            <td>
                                                @if($project->progresses->count()>0)
                                                    @foreach($project->progresses->sortBy('id') as $progress)
                                                        <a class="btn btn-success btn-xs" href="#" title="{{$progress->project_remarks}}">
                                                            {{$progress->month->name}}
                                                        </a>
                                                        <a class="btn btn-xs btn-warning" title="{{$progress->month->name}} को प्रगति सम्पादन गर्नुहोस" href="{{ route('progress.edit',$progress->id) }}"><span class="fa fa-pencil"></span></a>
                                                    @endforeach
                                                @else
                                                    No Progress submitted yet
                                                @endif
                                            </td>
                                            <td>
                                                @if($project->progresses->count()>0)
                                                    {!! $project->progresses->sortByDesc('id')->first()->project_remarks !!}
                                                @else
                                                    <span style="color:indianred;">हालसम्म कुनै प्रगति प्रबिस्ट नगरिएको</span>
                                                @endif

                                                    
                                            </td>

                                            <td>
                                                @if($project->status == 1)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">InActive</span>
                                                @endif
                                            </td>

                                            <td>

                                                @if(!isset($_GET['trashes']))
                                                    <a class="btn btn-xs btn-info" href=""><span class="fa fa-search"></span></a>
                                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                                    {!! delete_form(['progress.destroy',$project->slug], 'pro_my_form'.$i++) !!}
                                                @else

                                                    <a href="{{route('restore',['Project',$project->slug])}}" class="btn btn-xs btn-success showToolTip" title="Restore" role="button"><span class="fa fa-reply"></span></a>&nbsp;

                                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Permanent Delete" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="fa fa-times-circle"></span></a>&nbsp;
                                                    {!! hard_delete_form(['progress.destroy',$project->slug], 'pro_my_form'.$i++) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>

                            @if(isset($not_found))
                                <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                            @endif
                            {!! str_replace('/?', '?', $projects->appends(Request::input())->render()) !!}
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

@stop