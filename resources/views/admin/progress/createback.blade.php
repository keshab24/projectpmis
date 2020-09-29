@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <style type="text/css">
        .ui-widget{
            font-size: 12px;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a class="btn btn-info showToolTip" title="Show Progresses" href="{{route('progress.index')}}"><i class="glyphicon glyphicon-eye-open"></i> Progresses</a></li>
                    <li class="active"><a href="{{route('progress.create')}}"><i class="fa fa-plus"></i> Add Progresses</a></li>
                    <li class="active"><a href="{{route('set.destroy.session')}}"><i class="fa fa-exchange" style="color: #ff0000;"></i>Change Session (अर्को शिर्षक परिवर्तन गर्नुहोस )</a></li>
                    @if(session()->has('month_id_session'))
                    <li class="active">ब.ऊ.शी.न. {{ $budget_name_session[0] }}, ख.शी.न. {{ $expenditure_name_session[0] }} को {{ $month_name_session[0] }} महिनाको प्रगति चढाउनुहोस</li>
                    @endif
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                        </div>

                        <div class="col-md-2 col-lg-2 col-sm-2">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-info showToolTip" title="Total Procurements"><span class="badge">{{$total_items}}</span></button>
                                    <a href="{{route('procurement.index')}}?trashes=yes" class="btn btn-danger showToolTip" title="Trashes" role="button"><span class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    @if($not_found == false)
                        <div class="table-responsive pro_hide_y">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th width="5%">
                                        ब.उ.शी.नं.
                                    </th>
                                    <th width="35%">
                                        अायाेजना
                                    </th>
                                    <th width="8%">
                                        बिल खर्च
                                    </th>
                                    <th width="8%">
                                        कन्टिन्जेन्सि खर्च
                                    </th>
                                    <th width="22%">
                                        हालको अवस्था
                                    </th>
                                    <th width="27%">
                                        भौतिक प्रगति छान्नुहोस्
                                    </th>
                                </tr>

                                <?php $index=0; ?>
                                @if($projects->isEmpty())
                                    <tr>
                                        <td colspan="6">
                                            <div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i> Please add some Activities first!!</div>
                                        </td>
                                    </tr>
                                @else
                                    {!!Form::open(['route'=>'progress.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                                    @foreach($projects as $index => $project)
                                        <tr @if(isset($_GET['trashes'])) class="danger" @endif>
                                            <td>{!! $project->budget_topic->budget_topic_num !!}</td>
                                            <td>{!! $project->name !!}</td>
                                            <td>
                                                {!! Form::text('bill_exp[]',null,['class'=>'form-control','placeholder'=>'Bill Expenditure']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('cont_exp[]',null,['class'=>'form-control','placeholder'=>'Contingency Expenditure']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('project_remarks[]',null,['class'=>'form-control','placeholder'=>'आयोजनाको हालको अवस्था उल्लेख गर्नुहोस']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('pt_id[]',null,['class'=>'form-control','id'=>'pt_id'.$index,'placeholder'=>'भौतिक प्रगति']) !!}
                                            </td>
                                        </tr>
                                        {!! Form::hidden('project_code[]',$project->id) !!}
                                        {!! Form::hidden('fy_id[]',session()->get('pro_fiscal_year')) !!}
                                        {!! Form::hidden('month_id[]',session()->get('month_id_session')) !!}
                                        {!! Form::hidden('status[]','1') !!}
                                    @endforeach

                                    <div class="panel panel-default">
                                        <div class="panel-footer">
                                            <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save</button>
                                            <button class="btn btn-default" type="reset">Reset</button>
                                        </div>
                                    </div>

                                    {!! Form::close() !!}
                                @endif
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-sm-6">
                                {!! massAction('','local','Procurement') !!}
                            </div>
                        </div>
                        {!! str_replace('/?', '?', $projects->appends(Request::input())->render()) !!}
                    @else
                        <div class="alert alert-danger">
                            <i class="fa fa-frown-o"></i> {{$not_found}} <a class="btn btn-xs btn-danger" href="{{route('procurement.index')}}">All</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{{asset('public/admin/js/sweet-alert.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            @if(!session()->has('pro_budget_topic'))
            swal({
                        title: "निम्न विवरण चयन गर्नुहोस",
                        text: "",
                        type: "input",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        allowOutsideClick: false,
                        inputPlaceholder: "Budget Head"
                    },
                    function (inputValue) {
                        if (inputValue === false) return false;

                        if (inputValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }

                        $("#pro_budget_topic_form").trigger('submit');
                        //swal("Nice!", "You wrote: " + inputValue, "success");
                    });
            var $sweet_input = $(".sweet-alert input");
            $sweet_input.val(2070);
            $sweet_input.hide();
            var $form_input = '';
            $form_input += '<br>ब.उ.शी.नं.<br>{!! Form::select("budget_topic", $budget_topics, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}';
            $form_input += '<br>ख.उ.शी.नं.<br>{!! Form::select("expenditure_topic", $expenditure_topics, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}';
            $form_input += '<br>लागु गर्ने कार्यालय<br>{!! Form::select("implementing_office", $implementing_offices, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}';
            $form_input += '<br>महिना<br>{!! Form::select("month_id", $month_name, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}';
            $sweet_input.after('{!! Form::open(["route"=>"set.budget.topic","id"=>"pro_budget_topic_form", "method"=>"get"]) !!}'+$form_input+'{!! Form::close() !!}');
            @elseif(!session()->has('pro_expenditure_topic'))
            swal({
                        title: "Choose Expenditure Head",
                        text: "",
                        type: "input",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        allowOutsideClick: false,
                        inputPlaceholder: "Expenditure Head"
                    },
                    function (inputValue) {
                        if (inputValue === false) return false;

                        if (inputValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }

                        $("#pro_expenditure_topic_form").trigger('submit');
                        //swal("Nice!", "You wrote: " + inputValue, "success");
                    });
            var $sweet_input = $(".sweet-alert input");
            $sweet_input.val(2070);
            $sweet_input.hide();
            $sweet_input.after('{!! Form::open(["route"=>"set.expenditure.topic","id"=>"pro_expenditure_topic_form", "method"=>"get"]) !!}{!! Form::select("expenditure_topic", $expenditure_topics, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}{!! Form::close() !!}');
            @elseif(!session()->has('pro_implementing_office'))
            swal({
                        title: "Choose Implementing Office",
                        text: "",
                        type: "input",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        allowOutsideClick: false,
                        inputPlaceholder: "Implementing Office"
                    },
                    function (inputValue) {
                        if (inputValue === false) return false;

                        if (inputValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }

                        $("#pro_implementing_office_form").trigger('submit');
                        //swal("Nice!", "You wrote: " + inputValue, "success");
                    });
            var $sweet_input = $(".sweet-alert input");
            $sweet_input.val(2070);
            $sweet_input.hide();
            $sweet_input.after('{!! Form::open(["route"=>"set.implementing.office","id"=>"pro_implementing_office_form", "method"=>"get"]) !!}{!! Form::select("implementing_office", $implementing_offices, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}{!! Form::close() !!}');

            @elseif(!session()->has('pro_division_office'))
            swal({
                        title: "Choose Division Office",
                        text: "",
                        type: "input",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        allowOutsideClick: false,
                        inputPlaceholder: "Division Office"
                    },
                    function (inputValue) {
                        if (inputValue === false) return false;

                        if (inputValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }

                        $("#pro_division_code_form").trigger('submit');
                        //swal("Nice!", "You wrote: " + inputValue, "success");
                    });
            var $sweet_input = $(".sweet-alert input");
            $sweet_input.val(2070);
            $sweet_input.hide();
            $sweet_input.after('{!! Form::open(["route"=>"set.division.office","id"=>"pro_division_code_form", "method"=>"get"]) !!}{!! Form::select("division_code", $division_codes, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}{!! Form::close() !!}');
            @elseif(!session()->has('month_id_session'))
            swal({
                        title: "Choose Month",
                        text: "",
                        type: "input",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        allowOutsideClick: false,
                        inputPlaceholder: "Month Name"
                    },
                    function (inputValue) {
                        if (inputValue === false) return false;

                        if (inputValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }

                        $("#pro_month_id_form").trigger('submit');
                        //swal("Nice!", "You wrote: " + inputValue, "success");
                    });
            var $sweet_input = $(".sweet-alert input");
            $sweet_input.val(2070);
            $sweet_input.hide();
            $sweet_input.after('{!! Form::open(["route"=>"set.month.id","id"=>"pro_month_id_form", "method"=>"get"]) !!}{!! Form::select("month_id", $month_name, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}{!! Form::close() !!}');

            @endif

            $source = [<?php foreach($progress_tracks as $progress_track) echo '"'.$progress_track.'",'; ?>];

            @for($i = 0; $i<=$index; $i++)

            var $progress_tracks{{$i}} = $('#pt_id{{$i}}');
            $progress_tracks{{$i}}.tokenfield({
                autocomplete: {
                    source: $source,
                    delay: 100
                },
                createTokensOnBlur:true,
                showAutocompleteOnFocus: true
            });

            preventDuplicate($progress_tracks{{$i}});
            @endfor
        });
        function fill_input($val){
            $(".sweet-alert input").val($val);
        }
    </script>

@stop