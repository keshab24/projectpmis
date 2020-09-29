@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet"
          href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}"
          type="text/css"/>
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span>
                            Dashboard</a></li>
                    <li class="active"><a href="{{route('notice.index')}}"><i class="fa fa-comments"></i>
                            Notice</a></li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                        <div class="col-lg-6 col-md-6">
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-6 col-lg-6">

                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        {!!Form::open(['route'=>'notice_pushed','method'=>'post'])!!}
                        <table id="dataTableItems" class="table table-striped table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>
                                    Choose
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($listeners as $listener)
                                    <tr>
                                        <td>
                                            <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                                <input type="checkbox" name="select_item[]" id="select_item{{$listener->id}}" value="{{ $listener->id }}" class="select_me third_color" data-menu-id="{{$listener->id}}" />
                                                <label for="select_item{{$listener->id}}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $listener->name }}
                                        </td>
                                        <td>
                                            {{ $listener->email }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4">
                                    <!-- status -->
                                    <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                        <input type="checkbox" name="sms" id="sms" class="second_color"/>
                                        <label for="sms">Send SMS?</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <input type="submit" class="btn btn-success" value="Send Notification">
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                        {!! Form::close() !!}
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
    <script type="text/javascript"
            src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {
            $('#dataTableItems').dataTable( {
                "ordering": false
            } );
        });
    </script>
    <script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
@stop