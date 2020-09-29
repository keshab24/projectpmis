
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
                <li class="active"><a href="{{route('construction-located-area.index')}}"><span class="glyphicon glyphicon-list"></span> {{ $model }}</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    {!! Form::open(['route'=>"$route.index",'method'=>'get']) !!}
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <a href="{{route("$route.create")}}" class="btn btn-warning showToolTip pull-left" title="Add {{ $model }}" role="button"><span class="glyphicon glyphicon-plus"></span> Add {{ $model }}</a>

                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <div class="input-group">
                            {!! Form::text('search',$default_search,['placeholder'=>"Search $model",'class'=>'form-control firstInput']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-5 col-lg-5 col-sm-5">

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
                                <th width="10%">
                                    <a href="{{route("$route.index").'?orderBy=located_area&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Notification">Located Area</a>
                                </th>
                                <th width="10%">
                                    <a href="{{route("$route.index").'?orderBy=located_area_nep&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Notification">Located Area Nepali</a>
                                </th>

                                <th width="10%">
                                    <a href="{{route("$route.index").'?orderBy=created_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Created Date">Created At</a>
                                </th>
                                <th width="10%">
                                    <a href="{{route("$route.index").'?orderBy=updated_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>
                                </th>
                                <th width="5%">
                                    <a href="{{route("$route.index").'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Status</a>
                                </th>
                                <th width="13%">Action</th>
                            </tr>
                            <?php $i=1; ?>

                            @if($construction_located_areas->isEmpty())
                            <tr>
                                <td colspan="12">
                                    <div class="alert alert-warning" role="alert">Please add some {{ $model }} first!!</div>
                                </td>
                            </tr>
                            @else
                            @foreach($construction_located_areas as $construction_located_area)
                            <tr>
                                <td>
                                    <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                        <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$construction_located_area->id}}" />
                                        <label for="select_item{{$i}}"></label>
                                    </div>
                                </td>
                                <td>{{$construction_located_area->located_area}}</td>
                                <td>{{$construction_located_area->located_area_nep}}</td>
                                <td>{{$construction_located_area->created_at->diffForHumans()}}</td>
                                <td>{{$construction_located_area->updated_at->diffForHumans()}}</td>
                                <td>
                                    @if($construction_located_area->status==1)
                                    <span class="label label-success">Active</span>
                                    @else
                                    <span class="label label-danger">In-Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-warning" href="{{ route("$route.edit",$construction_located_area->id) }}"><span class="fa fa-pencil"></span></a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                        @if(isset($not_found))
                        <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                        @endif
                        {!! str_replace('/?', '?', $construction_located_areas->appends(Request::input())->render()) !!}
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