
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
                <li class="active"><a href="{{route('lumpsumbudget.index')}}"><span class="glyphicon glyphicon-list"></span> Lump Sum Budget</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    {!! Form::open(['route'=>'lumpsumbudget.index','method'=>'get']) !!}
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <a href="{{route('lumpsumbudget.create')}}" class="btn btn-warning showToolTip pull-left" title="Add Lumpsumbudget" role="button"><span class="glyphicon glyphicon-plus"></span> Add Lump Sum Budget</a>

                    </div>
                    <div class="col-md-5 col-lg-5 col-sm-5">
                        <div class="input-group">
                            {!! Form::text('search',$default_search,['placeholder'=>'Search Lump sum budget...','class'=>'form-control firstInput']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-5 col-lg-5 col-sm-5">
                        <a href="{{route('lumpsumbudget.index')}}?trashes=yes" class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                        {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','Lumpsumbudget') !!}
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-md-12 col-lg-12">
                    <div class="">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th width="5%" rowspan="2">
                                    <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select/Deselect all items">
                                        <input type="checkbox" name="select_all" id="select_all" class="sixth_color" />
                                        <label for="select_all"></label>
                                    </div>
                                </th>
                                <th rowspan="2" width="7%">
                                    <a href="{{route('lumpsumbudget.index').'?orderBy=expenditure_topic_id&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Notification">
                                    बजेट शीर्षक</a>
                                </th>
                                <th rowspan="2" width="8%">
                                    <a href="{{route('lumpsumbudget.index').'?orderBy=expenditure_topic_id&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Notification">
                                        खर्च शीर्षक
                                    </a>
                                </th>
                                <th colspan="5" class="text-center">
                                    चालु अा.व.काे
                                </th>
                                <th width="8%" rowspan="2">
                                    <a href="{{route('lumpsumbudget.index').'?orderBy=created_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Created Date">Created At</a>
                                </th>
                                <th width="10%" rowspan="2">
                                    <a href="{{route('lumpsumbudget.index').'?orderBy=updated_at&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>
                                </th>
                                <th width="5%" rowspan="2">
                                    <a href="{{route('lumpsumbudget.index').'?orderBy=status&order='.$order.$other_data}}" class="sortText showToolTip" title="Sort By Status">Status</a>
                                </th>
                                <th width="8%" rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>जम्मा बजेट</th>
                                <th>नेपाल सरकार</th>
                                <th>ऋृण</th>
                                <th>अनुदान</th>
                                <th>सोझै भुक्तानी</th>
                            </tr>
                            <?php $i=1; ?>

                            @if($lumpsumbudgetes->isEmpty())
                                <tr>
                                    <td colspan="12">
                                        <div class="alert alert-warning" role="alert">Please add some lump sum budget first!!</div>
                                    </td>
                                </tr>
                            @else
                                <?php
                                    $pro_budget_topic = '';
                                    $pro_exp_label = 'success';
                                ?>
                                @foreach($lumpsumbudgetes as $lumpsumbudget)
                                    @if($pro_budget_topic != $lumpsumbudget->budgettopic->budget_topic_num)
                                        <?php
                                        $pro_exp_label= $pro_exp_label == 'success'?'info':'success';
                                        ?>
                                    @endif
                                    <tr class="{{$pro_exp_label}}">
                                        <td>
                                            <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                                <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$lumpsumbudget->id}}" />
                                                <label for="select_item{{$i}}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            @if($pro_budget_topic != $lumpsumbudget->budgettopic->budget_topic_num)
                                                <?php
                                                    $pro_budget_topic = $lumpsumbudget->budgettopic->budget_topic_num;
                                                ?>
                                                {{$lumpsumbudget->budgettopic->budget_topic_num}}
                                                <span class="label label-{{$pro_exp_label}} label-xs">
                                                {{$lumpsumbudget->budgettopic->budget_head}}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$lumpsumbudget->expendituretopic->expenditure_topic_num}}
                                            <span class="label label-{{$pro_exp_label}} label-xs">
                                            {{$lumpsumbudget->expendituretopic->expenditure_head}}
                                            </span>
                                        </td>
                                        <td>{{$lumpsumbudget->total_budget}}</td>
                                        <td>{{$lumpsumbudget->gon}}</td>
                                        <td>{{$lumpsumbudget->loan}}</td>
                                        <td>{{$lumpsumbudget->grants}}</td>
                                        <td>{{$lumpsumbudget->direct_payments}}</td>
                                        <td>{{$lumpsumbudget->created_at}}</td>
                                        <td>{{$lumpsumbudget->updated_at}}</td>
                                        <td>
                                            @if($lumpsumbudget->status==1)
                                            <span class="label label-success">Active</span>
                                            @else
                                            <span class="label label-danger">In-Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!isset($_GET['trashes']))
                                                <a class="btn btn-xs btn-info" href=""><span class="fa fa-search"></span></a>
                                                <a class="btn btn-xs btn-warning" href="{{ route('lumpsumbudget.edit',$lumpsumbudget->id) }}"><span class="fa fa-pencil"></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        @if(isset($not_found))
                        <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                        @endif
{{--
                        {!! str_replace('/?', '?', $lumpsumbudgetes->appends(Request::input())->render()) !!}
--}}
                        <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                            {!! massAction('','local','Lumpsumbudget') !!}
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