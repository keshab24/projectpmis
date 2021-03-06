
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
                <li class="active"><a href="{{route('employee.index')}}"><span class="glyphicon glyphicon-list"></span> employee</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    {!! Form::open(['route'=>'employee.index','method'=>'get']) !!}
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <a href="{{route('employee.create')}}" class="btn btn-warning showToolTip pull-left" title="Add employee" role="button"><span class="glyphicon glyphicon-plus"></span> Add Employees</a>

                    </div>
                    <div class="col-md-5 col-lg-5 col-sm-5">
                        <div class="input-group">
                            {!! Form::text('search',$default_search,['placeholder'=>'Search Employees...','class'=>'form-control firstInput']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-5 col-lg-5 col-sm-5">
                        <a href="{{route('employee.index')}}?trashes=yes" class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                        {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','Employee') !!}
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
                                    <a href="{{route('employee.index').'?orderBy=notification&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Notification">Name</a>
                                </th>
                                <th width="20%">
                                    Address
                                </th>
                                <th>
                                    Alias
                                </th>
                                <th width="10%">
                                    Contact
                                </th>
                                <th width="10%">
                                    <a href="{{route('employee.index').'?orderBy=created_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Created Date">Created At</a>
                                </th>
                                <th width="10%">
                                    <a href="{{route('employee.index').'?orderBy=updated_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>
                                </th>
                                <th width="5%">
                                    <a href="{{route('employee.index').'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Status</a>
                                </th>
                                <th width="13%">Action</th>
                            </tr>
                            <?php $i=1; ?>

                            @if($employees->isEmpty())
                            <tr>
                                <td colspan="12">
                                    <div class="alert alert-warning" role="alert">Please add some employees first!!</div>
                                </td>
                            </tr>
                            @else
                            @foreach($employees as $employee)
                            <tr>
                                <td>
                                    <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                        <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$employee->id}}" />
                                        <label for="select_item{{$i}}"></label>
                                    </div>
                                </td>
                                <td>{{$employee->name}}</td>
                                <td>{{$employee->address}}</td>
                                <td>{{$employee->alias}}</td>
                                <td>{{$employee->contact}}</td>
                                <td>{{$employee->created_at}}</td>
                                <td>{{$employee->updated_at}}</td>
                                <td>
                                    @if($employee->status==1)
                                    <span class="label label-success">Active</span>
                                    @else
                                    <span class="label label-danger">In-Active</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!isset($_GET['trashes']))
                                    <a class="btn btn-xs btn-info" href="{{route('employee.show',$employee->slug) }}"><span class="fa fa-search"></span></a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('employee.edit',$employee->slug) }}"><span class="fa fa-pencil"></span></a>
                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                    {!! delete_form(['employee.destroy',$employee->slug], 'pro_my_form'.$i++) !!}
                                    @else
                                    <a href="{{route('restore',['Employee',$employee->slug])}}" class="btn btn-xs btn-success showToolTip" title="Restore" role="button"><span class="fa fa-reply"></span></a>&nbsp;

                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Permanent Delete" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="fa fa-times-circle"></span></a>&nbsp;
                                    {!! hard_delete_form(['employee.destroy',$employee->slug], 'pro_my_form'.$i++) !!}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                        @if(isset($not_found))
                        <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                        @endif
                        {!! str_replace('/?', '?', $employees->appends(Request::input())->render()) !!}
                        <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                            {!! massAction('','local','Employee') !!}
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