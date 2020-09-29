@extends('layouts.admin_layout')


@section('headerContent')

@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li class="active"><a href="{{route('user.index')}}"><i class="fa fa-buysellads"></i> Admin</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        <div class="col-lg-8 col-md-8">
                            <a href="{{route('user.create')}}" class="btn btn-warning showToolTip pull-left" title="Add New Admin" role="button"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs hidden-sm">Add Admin</span></a>
                            {!! Form::open(['route'=>'user.index','method'=>'get']) !!}
                                <div class="row">
                                    <div class="col-md-4 col-lg-4">
                                        {{--replaced the collective form because of the need of opt group --}}
                                        {{--implementing office ko child office pani chahiyeko le garda --}}
                                        @if($user_info->implementingOffice->isNewTown)
                                            {!! Form::select('implementing_office', $implementing_offices,isset($implementing_office)?$implementing_office:0, ['class'=>'form-control']) !!}
                                        @else
                                            {!! piuOfficesSelectList($implementing_offices_new_update, isset($implementing_office) ? $implementing_office : null) !!}
                                        @endif
                                    </div>
                                    <div class="col-md-2 col-lg-2">
                                        {!! Form::select('type_flag', add_my_array($type_flags,'User Type', ''), null, ['class'=>'form-control','placehoder'=>'User Type']) !!}
                                    </div>
                                    <div class="col-md-2 col-lg-1 pro_checkbox pro_no_top">
                                        <input type="checkbox" name="all-users" id="all-users" @if(isset($_GET['all-users'])) checked="checked" @endif class="first_color" value="1">
                                        <label for="all-users">All Users</label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control firstInput" placeholder="Search Admin..." value="{{ $_GET['search']??'' }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                                        </span>
                                        </div><!-- /input-group -->
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                            <p> Showing {{ $users->firstItem() }}
                                to {{ $users->lastItem() }} of {{ $users->total() }} entries </p>

                        </div>

                        <div class="col-md-4 col-lg-4">
                            {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','User') !!}
                            <a href="{{route('user.index')}}?trashes=yes" class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span class="glyphicon glyphicon-trash"></span> <span class="badge">{{$trashes_no}}</span></a>

                            @if(!isset($_GET['contractor']))
                                <a href="{{route('user.index')}}?contractor=yes" class="btn btn-info showToolTip pull-right" title="Contractor" role="button"><span class="fa fa-users"></span> <span class="badge"></span></a>
                            @else
                                <a href="{{route('user.index')}}" class="btn btn-info showToolTip pull-right" title="Employees" role="button"><span class="fa fa-users"></span> <span class="badge"></span></a>
                            @endif

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
                                    <th width="80px">
                                        Image
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Imp Office
                                    </th>
                                    <th width="10%">
                                        Access Level
                                    </th>
                                    <th width="10%">
                                        Contact
                                    </th>
                                    <th width="10%">
                                        Created By
                                    </th>
                                    <th width="10%">
                                        Last Modified
                                    </th>
                                    <th width="5%">
                                        Status
                                    </th>
                                    <th width="13%">Action</th>
                                </tr>
                                <?php 
                                    $i=1; 
                                    $imp_office = '';
                                ?>

                                @if($users->isEmpty())
                                    <tr>
                                        <td colspan="12">
                                            <div class="alert alert-warning" role="alert">Please add some user first!!</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($users as $user)
                                        <tr class="@if(isset($_GET['trashes'])) danger @endif" >
                                            <td>
                                                <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                                    <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$user->id}}" />
                                                    <label for="select_item{{$i}}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="index_image_thumb">
                                                    @if($user->image !='' && $user->image != NULL && file_exists('public/images/users/thumbnail/thumbvtx'.$user->image))
                                                        <img src="{{asset('public/images/users/thumbnail/thumbvtx'.$user->image)}}" alt="{{$user->image}}" title="{{$user->name}}" class="img-thumbnail" />
                                                    @else
                                                        <img src="{{asset('public/images/no_image_thumb.png')}}" alt="no image" title="No Image!!" class="img-thumbnail" />
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route('user.edit',$user->slug)}}" title="Edit" role="button">{!! $user->name !!}</a>
                                                <br>
                                                <small>
                                                Type : {{ (optional($user->typeFlag)->type)?:'N/A' }}
                                            </small>
                                            </td>
                                            <td>
                                                @if(optional($user->implementingOffice)->title != $imp_office)
                                                    {{ optional($user->implementingOffice)->title }}
                                                    <?php $imp_office = optional($user->implementingOffice)->title?>
                                                @else
                                                 <p class="text-center">"</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->access === 'Root Level')
                                                    <span class="label label-success">{!! $user->access !!}</span>
                                                @elseif($user->access === 'Top Level')
                                                    <span class="label label-warning">{!! $user->access !!}</span>
                                                @elseif($user->access === 'Limited')
                                                    <span class="label label-info">{!! $user->access !!}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $user->email }} <br> {{ $user->phone }}
                                            </td>
                                            <td>
                                                {!! $user->creator?$user->creator->name:'N/A' !!}
                                                <br>on {!! date_display($user->created_at) !!}
                                            </td>
                                            <td>
                                            @if($user->created_at != $user->updated_at)
                                                    {!! date_display($user->updated_at).' by <em>'.optional($user->updator)->name.'</em>' !!}
                                                @else
                                                    <span class="none_text">Not Modified Yet!!</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!isset($_GET['trashes']))
                                                    @if($user->status == 0)
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
                                                    <a href="{{route('user.show',$user->slug)}}" class="btn btn-xs btn-info showToolTip" title="View" role="button"><span class="glyphicon glyphicon-zoom-in"></span></a>&nbsp;
                                                    <a href="{{route('user.edit',$user->slug)}}" class="btn btn-xs btn-warning showToolTip" title="Edit" role="button"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                                @else
                                                    <a href="{{route('restore',['User',$user->slug])}}" class="btn btn-xs btn-success showToolTip" title="Restore" role="button"><span class="fa fa-reply"></span></a>&nbsp;
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                            @if(isset($not_found) && $not_found != '')
                                <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                            @endif
                            <p> Showing {{ $users->firstItem() }}
                                to {{ $users->lastItem() }} of {{ $users->total() }} entries </p>

                            {!! str_replace('/?', '?', $users->appends(Request::input())->render()) !!}
                            <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                                {!! massAction('','local','User') !!}
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
@stop