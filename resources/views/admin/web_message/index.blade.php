
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
                <li class="active"><a href="{{route("$route.index")}}"><span class="glyphicon glyphicon-list"></span> Web Message</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-1 col-md-2 col-sm-1" {{ restrictToImplementingOffice() }}>
                        <a href="{{route("$route.create")}}" class="btn btn-warning showToolTip pull-left" title="Web Message" role="button"><span class="glyphicon glyphicon-plus"></span> Web Message</a>
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
                                    <a href="{{route("$route.index").'?orderBy=notification&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Notification">Tole</a>
                                </th>
                                <th>
                                    Title
                                </th>
                                <th width="13%">Created </th>
                                <th width="13%">Action</th>
                            </tr>
                            <?php $i=1; ?>
                            @forelse($web_message as $message)
                            <tr>
                                <td>
                                    <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                        <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$message->id}}" />
                                        <label for="select_item{{$i}}"></label>
                                    </div>
                                </td>
                                <td>{{$message->title}}</td>
                                <td>{{$message->description}}</td>
                                <td>{{$message->created_at->diffForHumans()}}</td>
                                <td>
                                    <a class="btn btn-xs btn-warning" title="Message Edit" href="{{ route("$route.edit",$message->id) }}"><span class="fa fa-pencil"></span></a>
                                    <a class="btn btn-xs btn-info" title="Message Forward" href="{{route("$route.show",$message->id)}}"><span class="fa fa-search"></span></a>
                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                    {!! delete_form(["$route.destroy",$message->id], 'pro_my_form'.$i++) !!}

                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="warning text-center" colspan="4">
                                        No Item Found
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                        @if(isset($not_found))
                        <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                        @endif
                        {!! str_replace('/?', '?', $web_message->appends(Request::input())->render()) !!}
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