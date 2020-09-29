@extends('layouts.admin_layout')
@section('headerContent')
    {{--<link href="{{ asset('public/admin/css/pro_map.css') }}" rel="stylesheet" type="text/css">--}}
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css"/>
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('imports')}}"><span class="glyphicon glyphicon-blackboard"></span> Imports</a>
                    </li>
                    <li><a href="{{route('project.index')}}"><i class="fa fa-buysellads"></i> Project</a></li>
                </ol>
                <h3 class="text-center">Manage Notification</h3>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <td>User</td>
                                <td>Type</td>
                            </tr>
                            {!!Form::open(['route'=>'notificationManagerPost','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                             @foreach($userTypes as $userType)
                                        <tr>
                                            <td>
                                                {{ preg_replace( '/([a-z0-9])([A-Z])/', "$1 $2", $userType->type ) }}
                                            </td>
                                            <td>
                                                @foreach($notificationTypes as $notificationType)
                                                        <?php
                                                            $checked=$checked=$userType->NotificationType->lists('id')->toArray();
                                                        ?>
                                                        <input type="checkbox" @if(in_array($notificationType->id,$checked)) checked="checked" @endif name="notifications[{{ $userType->id }}][{{ $notificationType->id }}]" value="{{ $notificationType->id }}">
                                                        {{ $notificationType->type }}
                                                @endforeach
                                            </td>
                                        </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2">
                                        <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                            Save
                                        </button>
                                    </td>
                                </tr>
                        {!! Form::close() !!}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    {{--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJQaz6AqTOGmByI43EqJd0SNEWz11FFjY&libraries=places"></script>--}}
    {{--<script type="text/javascript" src="{{asset('public/admin/js/google_map.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{asset('public/admin/plugin/select2/js/select2.full.min.js')}}"></script>--}}
    <script type="text/javascript">
        $(document).ready(function () {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
            $('#district_id').change(function () {
                $did = $('#district_id').find(":selected").val();
                $.ajax({
                    url: "{{route('ajax.get.project.code')}}",
                    data: {
                        "district_id": $did,
                    },
                    cache: false,
                    type: "GET",
                    success: function (response) {
                        var json = JSON.parse(response);

                        $("#project_code").val(json.project_code);
                        $('#implementing_office_id').val(json.office_id).change();
                    },
                    error: function (xhr) {

                    }
                });
            });
            $('#whose').css("visibility", "hidden");
            $(".swamittwo").change(function () {
                var raheko = $(".swamittwo").val();
                if (raheko == "1") {
                    $('#whose').css("visibility", "visible");
                }
                else {
                    $('#whose').css("visibility", "hidden");
                }
            });
            $('#shitnumber').css("visibility", "hidden");
            $('#kittanumber').css("visibility", "hidden");
            $(".land_ownership").change(function () {
                var lalpurja = $(".land_ownership").val();
                if (lalpurja == "1") {
                    $('#shitnumber').css("visibility", "visible");
                    $('#kittanumber').css("visibility", "visible");

                }
                else {
                    $('#shitnumber').css("visibility", "hidden");
                    $('#kittanumber').css("visibility", "hidden");

                }
            });

        });

    </script>
@stop