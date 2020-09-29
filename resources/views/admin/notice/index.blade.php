@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li class="active"><a href="{{route('notice.index')}}"><i class="fa fa-comments"></i> Notice</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        <div class="col-lg-6 col-md-6">
                            <a href="{{route('notice.create')}}" class="btn btn-warning showToolTip pull-left" title="Add New Notice" role="button"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs hidden-sm">Add Notice</span></a>
                            {!! Form::open(['route'=>'notice.index','method'=>'get']) !!}
                            <div class="input-group">
                                <input type="text" name="search" class="form-control firstInput" placeholder="Search Notice...">
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                            </span>
                            </div><!-- /input-group -->
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','Notice') !!}
                            <a href="{{route('notice.index')}}?trashes=yes" class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span class="glyphicon glyphicon-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th width="5%">
                                        <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select/Deselect all items">
                                            <input type="checkbox" name="select_all" id="select_all" class="sixth_color" />
                                            <label for="select_all"></label>
                                        </div>
                                    </th>
                                    <th>
                                        <a href="{{route('notice.index').'?orderBy=name&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Name">Name</a>
                                    </th>
                                    <th width="10%">
                                        Nepali Name
                                    </th>
                                    <th width="10%">
                                        <a href="{{route('notice.index').'?orderBy=created_by&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Creator">Created By</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{route('notice.index').'?orderBy=created_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Created Date">Created At</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{route('notice.index').'?orderBy=updated_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>

                                    </th>
                                    <th width="5%">
                                        <a href="{{route('notice.index').'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Status</a>
                                    </th>
                                    <th width="13%">Action</th>
                                </tr>
                                <?php $i=1; ?>
                                @if($notices->isEmpty())
                                    <tr>
                                        <td colspan="12">
                                            <div class="alert alert-warning" role="alert">Please add some notices first!!</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($notices as $notice)
                                        <tr class="@if(isset($_GET['trashes'])) danger @endif" >
                                            <td>
                                                <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                                    <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$notice->id}}" />
                                                    <label for="select_item{{$i}}"></label>
                                                </div>
                                            </td>
                                            <td><a href="{{route('notice.edit',$notice->slug)}}" title="Edit" role="button">{!! $notice->name !!}</a></td>
                                            <td><a href="{{route('notice.edit',$notice->slug)}}" title="Edit" role="button">{!! $notice->name_nep !!}</a></td>
                                            <td>
                                                {!! $notice->creator->name !!}
                                            </td>
                                            <td>{!! date_display($notice->created_at) !!}</td>
                                            <td>
                                                @if($notice->created_at != $notice->updated_at)
                                                    {!! date_display($notice->updated_at).' by <em>'.$notice->updator->name.'</em>' !!}
                                                @else
                                                    <span class="none_text">Not Modified Yet!!</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!isset($_GET['trashes']))
                                                    @if($notice->status == 1)
                                                        <span class="label label-success">Active</span>
                                                    @else
                                                        <span class="label label-danger">InActive</span>
                                                    @endif
                                                @else
                                                    <span class="label label-danger">Moved to Trash</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!isset($_GET['trashes']))
                                                    <a href="" class="btn btn-xs btn-info showToolTip" title="View" role="button"><span class="glyphicon glyphicon-zoom-in"></span></a>&nbsp;
                                                    <a href="{{route('notice.edit',$notice->slug)}}" class="btn btn-xs btn-warning showToolTip" title="Edit" role="button"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                                    <a href="#" class="btn btn-danger btn-xs showToolTip confirmButton" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                                    {!! delete_form(['notice.destroy',$notice->slug], 'pro_my_form'.$i++) !!}
                                                @else
                                                    <a href="{{route('restore',['Notice',$notice->slug])}}" class="btn btn-xs btn-success showToolTip" title="Restore" role="button"><span class="fa fa-reply"></span></a>&nbsp;
                                                    <a href="#" class="btn btn-danger btn-xs showToolTip confirmButton" title="Delete" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="fa fa-times-circle"></span></a>&nbsp;
                                                    {!! hard_delete_form(['notice.destroy',$notice->slug], 'pro_my_form'.$i++) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                            @if(isset($not_found))
                                <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                            @endif
                            {!! str_replace('/?', '?', $notices->appends(Request::input())->render()) !!}
                            <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                                {!! massAction('','local','Notice') !!}
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
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $('.pro_token_field').tokenfield();
        });
    </script>
@stop