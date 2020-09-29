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
                    <li class="active"><a href="{{route('release.index')}}"><span class="glyphicon glyphicon-list"></span> Release</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers text-right" data-appear-animation="fadeInDown" data-appear-delay="800">

                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Choose Implementing Office</h3>
                                    {!! minimizeButton('pro_collapse_implementing_office') !!}
                                </div>
                                <div class="panel-body collapse in implementing_office_show" id="pro_collapse_implementing_office">
                                    <ul>
                                        @foreach($implementingoffices as $implementingoffice)
                                            <li>
                                                @if($implementingoffice->is_physical_office ==1)
                                                    <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                @else
                                                    <a href="#">{{$implementingoffice->name}}</a>
                                                @endif
                                                @if($implementingoffice->child()->count()>0)
                                                    <ul>
                                                        @foreach($implementingoffice->child as $implementingoffice)
                                                            <li>
                                                                @if($implementingoffice->is_physical_office ==1)
                                                                    <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                @else
                                                                    <a href="#">{{$implementingoffice->name}}</a>
                                                                @endif
                                                                @if($implementingoffice->child()->count()>0)
                                                                    <ul>
                                                                        @foreach($implementingoffice->child as $implementingoffice)
                                                                            <li>
                                                                                @if($implementingoffice->is_physical_office ==1)
                                                                                    <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                                @else
                                                                                    <a href="#">{{$implementingoffice->name}}</a>
                                                                                @endif
                                                                                @if($implementingoffice->child()->count()>0)
                                                                                    <ul>
                                                                                        @foreach($implementingoffice->child as $implementingoffice)
                                                                                            <li>
                                                                                                @if($implementingoffice->is_physical_office ==1)
                                                                                                    <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                                                @else
                                                                                                    <a href="#">{{$implementingoffice->name}}</a>
                                                                                                @endif
                                                                                                @if($implementingoffice->child()->count()>0)
                                                                                                    <ul>
                                                                                                        @foreach($implementingoffice->child as $implementingoffice)
                                                                                                            <li>
                                                                                                                @if($implementingoffice->is_physical_office ==1)
                                                                                                                    <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                                                                @else
                                                                                                                    <a href="#">{{$implementingoffice->name}}</a>
                                                                                                                @endif
                                                                                                            </li>
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                @endif
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><i class="fa fa-money"></i> पेस्की तथा निकाशको विवरण</h3>
                                    {!! minimizeButton('pro_collapse_implementing_office') !!}
                                </div>
                                <div class="panel-body collapse in implementing_office_show" id="pro_collapse_implementing_office">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select/Deselect all items">
                                                    <input type="checkbox" name="select_all" id="select_all" class="sixth_color" />
                                                    <label for="select_all"></label>
                                                </div>
                                            </th>
                                            <th>कार्यक्रम/ आयोजना</th>
                                            <th>जम्मा बजेट</th>
                                            <th>हालसम्मको निकासा</th>
                                            <th>पछिल्लो  निकासा</th>
                                            <th>रकम</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;?>
                                        @foreach($implementing_office->projects as $project)
                                            <tr>
                                                <td><div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                                        <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$project->id}}" />
                                                        <label for="select_item{{$i++}}"></label>
                                                    </div></td>
                                                <td>
                                                    {{$project->name}}
                                                </td>
                                                <td>
                                                    {{$project->procurement->estimated_amount}}
                                                </td>
                                                <td>
                                                    {{$project->releases->sum('amount')}}
                                                </td>
                                                <td>
                                                    @if($project->releases->count()>0)
                                                    <span class="label label-info">{{rank_nepali($project->releases->count())}} निकासा समेत</span>
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$project->releases->count()>0?$project->releases->sortByDesc('id')->first()->amount:'-'}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <a href="{{route('implementingoffice.release.create',$implementing_office->id)}}" class="btn btn-success showToolTip pull-right" title="Release" role="button"><span class="glyphicon glyphicon-plus"></span> नयाँ निकासा</a>
                                    <div class="clearfix"></div>
                                </div>
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