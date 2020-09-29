
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
                <li class="active"><a href="{{route(strtolower($title).'.index')}}"><span class="glyphicon glyphicon-list"></span> {{ $title }}</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    {!! Form::open(['route'=>strtolower($title).'.index','method'=>'get']) !!}
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <a href="{{route(strtolower($title).'.create')}}" class="btn btn-warning showToolTip pull-left" title="Add contractor" role="button"><span class="glyphicon glyphicon-plus"></span> Add {{$title}}</a>

                    </div>
                    <div class="col-md-5 col-lg-5 col-sm-5">
                        <div class="input-group">
                            {!! Form::text('search',$default_search,['placeholder'=>'Search Contractors...','class'=>'form-control firstInput']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-5 col-lg-5 col-sm-5">
                        <a href="{{route(strtolower($title).'.index')}}?trashes=yes" class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                        {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','Contractor') !!}
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
                                <th width="20%">
                                    <a href="{{route(strtolower($title).'.index').'?orderBy=name&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Notification">Name</a>
                                </th><th width="20%">
                                    Address
                                </th>
                                <th width="10%">
                                    Contact
                                </th>
                                <th width="10%">
                                    <a href="{{route(strtolower($title).'.index').'?orderBy=created_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Created Date">Created At</a>
                                </th>
                                <th width="10%">
                                    <a href="{{route(strtolower($title).'.index').'?orderBy=updated_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>
                                </th>
                                <th width="5%">
                                    <a href="{{route(strtolower($title).'.index').'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Status</a>
                                </th>
                                <th width="13%">Action</th>
                            </tr>
                            <?php $i=1; ?>

                            @if($contractors->isEmpty())
                            <tr>
                                <td colspan="12">
                                    <div class="alert alert-warning" role="alert">Please add some contractors first!!</div>
                                </td>
                            </tr>
                            @else
                            @foreach($contractors as $contractor)
                            <tr>
                                <td>
                                    <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                        <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$contractor->id}}" />
                                        <label for="select_item{{$i}}"></label>
                                    </div>
                                </td>
                                <td>{{$contractor->name}}</td>
                                <td>{{$contractor->address}}</td>
                                <td>{{$contractor->contact}}</td>
                                <td>{{$contractor->created_at}}</td>
                                <td>{{$contractor->updated_at}}</td>
                                <td>
                                    @if($contractor->status==1)
                                    <span class="label label-success">Active</span>
                                    @else
                                    <span class="label label-danger">In-Active</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!isset($_GET['trashes']))
                                    <a class="btn btn-xs btn-info" href="{{ route(strtolower($title).'.show',$contractor->slug) }}"><span class="fa fa-search"></span></a>
                                    <a class="btn btn-xs btn-warning" href="{{ route(strtolower($title).'.edit',$contractor->slug) }}"><span class="fa fa-pencil"></span></a>
{{--
                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                    {!! delete_form([strtolower($title).'.destroy',$contractor->slug], 'pro_my_form'.$i++) !!}
                                    @else
                                    <a href="{{route('restore',['Contractor',$contractor->slug])}}" class="btn btn-xs btn-success showToolTip" title="Restore" role="button"><span class="fa fa-reply"></span></a>&nbsp;
                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Permanent Delete" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="fa fa-times-circle"></span></a>&nbsp;
                                    {!! hard_delete_form([strtolower($title).'.destroy',$contractor->slug], 'pro_my_form'.$i++) !!}
--}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                        @if(isset($not_found))
                        <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                        @endif
                        {!! str_replace('/?', '?', $contractors->appends(Request::input())->render()) !!}
                        <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                            {!! massAction('','local','Contractor') !!}
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