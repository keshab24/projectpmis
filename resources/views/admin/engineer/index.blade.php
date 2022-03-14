@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{ asset('public/admin/plugin/lightbox/css/lightbox.css') }}" type="text/css" />
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{ route('home') }}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li class="active"><a href="{{ route('engineers.index') }}"><span
                                class="glyphicon glyphicon-list"></span> Human Resource's Info</a></li>
                </ol>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        {!! Form::open(['route' => 'engineers.index', 'method' => 'get']) !!}
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <a href="{{ route('engineers.create') }}" class="btn btn-warning showToolTip pull-left"
                                title="Add Human Resource" role="button"><span class="glyphicon glyphicon-plus"></span> Add
                                Human Resource's Info</a>
                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-5">
                            <div class="input-group">
                                {!! Form::text('search', $default_search, ['placeholder' => 'Search Human Resource...', 'class' => 'form-control firstInput']) !!}
                                <span class="input-group-btn">
                                    <button class="btn btn-success" type="submit"><span
                                            class="glyphicon glyphicon-filter"></span> <span
                                            class="hidden-xs hidden-sm">Filter!</span></button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                        {!! Form::close() !!}
                        <div class="col-md-5 col-lg-5 col-sm-5">
                            <a href="{{ route('engineers.index') }}?trashes=yes"
                                class="btn btn-danger showToolTip pull-right" title="Trashes" role="button"><span
                                    class="fa fa-trash"></span> <span
                                    class="badge">{{ $trashes_no }}</span></a>
                            {!! massAction('col-md-9 col-lg-9 col-sm-9 col-xs-9 pull-left', 'local', 'Engineer') !!}
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th width="5%">
                                        <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip"
                                            title="Select/Deselect all items">
                                            <input type="checkbox" name="select_all" id="select_all"
                                                class="sixth_color" />
                                            <label for="select_all"></label>
                                        </div>
                                    </th>
                                    <th width="100px">
                                        Image
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>Implementing Office</th>
                                    <th width="10%">
                                        <a href="{{ route('engineers.index') . '?orderBy=category&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Category">Category</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{ route('engineers.index') . '?orderBy=type&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Designation">Designation</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{ route('engineers.index') . '?orderBy=home_address&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Address">Address</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{ route('engineers.index') . '?orderBy=email&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Email">Email</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{ route('engineers.index') . '?orderBy=mobile&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Phone">Mobile</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{ route('engineers.index') . '?orderBy=created_at&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Created Date">Created At</a>
                                    </th>
                                    <th width="10%">
                                        <a href="{{ route('engineers.index') . '?orderBy=updated_at&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Last Modified">Last Modified</a>
                                    </th>
                                    <th width="5%">
                                        <a href="{{ route('engineers.index') . '?orderBy=status&order=' . $order . $other_data }}"
                                            class="sortText showToolTip" title="Sort By Status">Status</a>
                                    </th>
                                    <th width="13%">Action</th>
                                </tr>
                                <?php $i = 1; ?>

                                @if ($engineers->isEmpty())
                                    <tr>
                                        <td colspan="12">
                                            <div class="alert alert-warning" role="alert">Please add some Human Resources
                                                first!!</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($engineers as $engineer)
                                        <tr>
                                            <td>
                                                <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip"
                                                    title="Select">
                                                    <input type="checkbox" name="select_item[]"
                                                        id="select_item{{ $i }}" class="select_me third_color"
                                                        data-menu-id="{{ $engineer->id }}" />
                                                    <label for="select_item{{ $i }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="index_image_thumb">
                                                    @if ($engineer->image != '' && $engineer->image != null && file_exists('admin/images/engineer/thumbnail/thumbvtx' . $engineer->image))
                                                        <img src="{{ asset('admin/images/engineer/thumbnail/thumbvtx' . $engineer->image) }}"
                                                            alt="{{ $engineer->image }}" title="{{ $engineer->name }}"
                                                            class="img-thumbnail" />
                                                    @else
                                                        <img src="{{ asset('admin/images/no_image_thumb.png') }}"
                                                            alt="no image" title="No Image!!" class="img-thumbnail" />
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $engineer->name }}</td>
                                            <td>{{ $engineer->implementingOffice->getOriginal()['name'] }}</td>
                                            <td>{{ manpowerTypes()[$engineer->category == 0 ? 1 : $engineer->category] }}
                                            </td>
                                            <td>{{ $engineer->type }}</td>
                                            <td>{{ $engineer->home_address }}</td>
                                            <td>{{ $engineer->email }}</td>
                                            <td>{{ $engineer->mobile }}</td>
                                            <td>{{ $engineer->created_at }}</td>
                                            <td>{{ $engineer->updated_at }}</td>
                                            <td>
                                                @if ($engineer->status == 1)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">In-Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!isset($_GET['trashes']))
                                                    <a class="btn btn-xs btn-info" href=""><span
                                                            class="fa fa-search"></span></a>
                                                    <a class="btn btn-xs btn-warning"
                                                        href="{{ route('engineers.edit', $engineer->slug) }}"><span
                                                            class="fa fa-pencil"></span></a>
                                                    {{-- <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                    {!! delete_form(['engineers.destroy',$engineer->slug], 'pro_my_form'.$i++) !!}
                                    @else
                                    <a href="{{route('restore',['Engineer',$engineer->slug])}}" class="btn btn-xs btn-success showToolTip" title="Restore" role="button"><span class="fa fa-reply"></span></a>&nbsp;

                                    <a class="btn btn-danger btn-xs showToolTip confirmButton" href="" title="Permanent Delete" data-placement="top" data-form-id="pro_my_form{{$i}}"><span class="fa fa-times-circle"></span></a>&nbsp;
                                    {!! hard_delete_form(['engineers.destroy',$engineer->slug], 'pro_my_form'.$i++) !!} --}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                            @if (isset($not_found))
                                <div class="alert alert-danger" role="alert">{!! $not_found !!}</div>
                            @endif
                            {!! str_replace('/?', '?', $engineers->appends(Request::input())->render()) !!}
                            <div class="col-md-3 col-lg-3 col-sm-6 massAction">
                                {!! massAction('', 'local', 'Engineer') !!}
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
    <script type="text/javascript" src="{{ asset('public/admin/js/check_all.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/admin/plugin/lightbox/js/lightbox.js') }}"></script>
@stop
