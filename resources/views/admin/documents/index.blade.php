@extends('layouts.admin_layout')


@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/lightbox/css/lightbox.css')}}" type="text/css"/>
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li class="active"><a href="{{route('document.index')}}"><span
                                    class="glyphicon glyphicon-blackboard"></span> Document</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        {!! Form::open(['route'=>'document.index','method'=>'get']) !!}
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <a href="{{route('document.create')}}"
                               class="btn btn-warning showToolTip pull-left" title="Add Document" role="button"><span
                                        class="glyphicon glyphicon-plus"></span> Add Document</a>

                        </div>
                        {!! Form::close() !!}
                        <div class="col-md-5 col-lg-5 col-sm-5">
                            <a href="{{route('document.index')}}?trashes=yes"
                               class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span
                                        class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                            {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left','local','Document') !!}
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>
                                        <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip"
                                             title="Select/Deselect all items">
                                            <input type="checkbox" name="select_all" id="select_all"
                                                   class="sixth_color"/>
                                            <label for="select_all"></label>
                                        </div>
                                    </th>
                                    <th>
                                        Title
                                    </th>
                                    <th>Category</th>
                                    <th>
                                        <a href="{{route('document.index').'?order='.$order.$other_data}}"
                                           class="sortText showToolTip" title="Sort By Name">Description</a>
                                    </th>
                                    <th>
                                        <a href="{{route('document.index').'?orderBy=created_at&order='.$order.$other_data}}"
                                           class="sortText showToolTip" title="Sort By Created Date">Created At</a>
                                    </th>
                                    <th>
                                        <a href="{{route('document.index').'?orderBy=updated_at&order='.$order.$other_data}}"
                                           class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>
                                    </th>
                                    <th>
                                        Parent ID
                                    </th>
                                    <th>
                                        Document
                                    </th>
                                    <th>Action</th>
                                </tr>

                                @if($documents->isEmpty())
                                    <tr>
                                        <td colspan="12">
                                            <div class="alert alert-warning" role="alert">Please add some documents
                                                first!!
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <?php $i = 1; ?>
                                    @foreach($documents as $document)
                                        <tr @if(isset($_GET['trashes'])) class="danger" @endif>
                                            <td>
                                                <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip"
                                                     title="Select">
                                                    <input type="checkbox" name="select_item[]" id="select_item{{$i}}"
                                                           class="select_me third_color"
                                                           data-menu-id="{{$document->id}}"/>
                                                    <label for="select_item{{$i}}"></label>
                                                </div>
                                            </td>
                                            <td>{!! $document->title !!}</td>
                                            <td>
                                                @if(isset(documentCategory()[$document->category_id]))
                                                    <a href="{{route('document.index').'?category='.$document->category_id}}" class="showToolTip pro_link" title="Click to view all in this category">
                                                        {{ documentCategory()[$document->category_id]}}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{!! $document->description !!}</td>
                                            <td>{!! $document->created_at !!}</td>
                                            <td>{!! $document->updated_at !!}</td>
                                            <td>{!! $document->parent_id !!}</td>
                                            <td>
                                                <a href="{!! asset('public/document/'.$document->attachment) !!}">Show</a>
                                            </td>
                                            <td>
                                                @if(!isset($_GET['trashes']))
                                                    <a class="btn btn-xs btn-info" href=""><span
                                                                class="fa fa-search"></span></a>
                                                    {{--<a class="btn btn-xs btn-warning" href="{{ route('document.edit',$document->id) }}"><span class="fa fa-pencil"></span></a>--}}
                                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href=""
                                                       title="Move to Trash" data-placement="top"
                                                       data-form-id="pro_my_form{{$i}}"><span
                                                                class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                                    {!! delete_form(['document.destroy',$document->id], 'pro_my_form'.$i++) !!}
                                                @else
                                                    <a href="{{route('restore',['Document',$document->id])}}"
                                                       class="btn btn-xs btn-success showToolTip" title="Restore"
                                                       role="button"><span class="fa fa-reply"></span></a>&nbsp;

                                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href=""
                                                       title="Permanent Delete" data-placement="top"
                                                       data-form-id="pro_my_form{{$i}}"><span
                                                                class="fa fa-times-circle"></span></a>&nbsp;
                                                    {!! hard_delete_form(['document.destroy',$document->id], 'pro_my_form'.$i++) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                            @if(isset($not_found))
                                <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                            @endif
                            {!! str_replace('/?', '?', $documents->appends(Request::input())->render()) !!}
                            <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                                {!! massAction('','local','Document') !!}
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