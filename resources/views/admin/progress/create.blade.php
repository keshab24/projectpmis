@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet"
          href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}"
          type="text/css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <style type="text/css">
        .ui-widget {
            font-size: 12px;
        }
    </style>
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li><a class="btn btn-info showToolTip" title="Show Progresses"
                           href="{{route('progress.index')}}"><i class="glyphicon glyphicon-eye-open"></i>
                            Progresses</a></li>
                    <li class="active"><a href="{{route('progress.create')}}"><i class="fa fa-plus"></i> Add
                            Progresses</a></li>
                    <li class="active"><a href="{{route('set.destroy.session')}}" style="color:lightseagreen;"><i
                                    class="fa fa-exchange" style="color: #ff0000;"></i>Change Session (अर्को शिर्षक
                            परिवर्तन गर्नुहोस )</a></li>
                    @if(session()->has('month_id_session'))
                        <li class="active">ब.ऊ.शी.न. <span style="color:red;">{{ $budget_name_session[0] }}</span>,
                            ख.शी.न. <span style="color:red;">{{ $expenditure_name_session[0] }}</span>, <span
                                    style="color:red;">
                            @if(session()->has('pro_implementing_office'))
                                    <?php
                                    if (session('pro_implementing_office') != 0) {
                                        $implementing_office = \PMIS\ImplementingOffice::whereId(session()->get('pro_implementing_office'))->first();
                                        if ($implementing_office->slug != 'division') {
                                            echo $implementing_office->name;
                                        } else {
                                            if (session()->has('pro_division_office')) {
                                                echo $pro_division_office[0];
                                            }
                                        }
                                    } else {
                                        echo "All Implementing Offices";
                                    }
                                    ?>
                                @endif
                        </span> को <span style="color:red;">{{ $month_name_session[0] }}</span> महिनाको प्रगति चढाउनुहोस
                        </li>
                    @endif
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-7">
                        </div>
                    </div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <?php
                    $index = 0;
                    ?>
                    @if($choose_session==false)
                        <div class="table-responsive pro_hide_y">
                            {!!Form::open(['route'=>'progress.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th width="5%">
                                        ब.उ.शी.नं.
                                    </th>
                                    <th width="15%">
                                        अायाेजना
                                    </th>
                                    <th width="8%">
                                        बिल खर्च (रु हजारमा)
                                    </th>
                                    <th width="5%">
                                        कन्टिन्जेन्सि रकम
                                    </th>
                                    <th width="7%">
                                        कन्टिन्जेन्सि खर्च
                                    </th>
                                    <th width="8%">
                                        हालको भौतिक प्रगति मात्र (%)
                                    </th>
                                    <th width="15%">
                                        हालको अवस्था
                                    </th>
                                    <th width="8%">
                                        अवस्था

                                    </th>
                                    <th width="8%">
                                        भौतिक प्रगति छान्नुहोस्
                                    </th>
                                    <th width="8%">Payment Status</th>
                                    <th width="8%">मिति</th>
                                </tr>
                                <?php
                                $count = 0;
                                ?>
                                @if($projects->isEmpty())
                                    <tr>
                                        <td colspan="6">
                                            <div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i>
                                                Budget Allocation Required before submitting progress
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($projects as $index => $project)
                                        <?php
                                        $count++;
                                        $checkIfThisProgressExist = $project->progresses()->whereMonthId(intval(session()->get('month_id_session')))->where('fy_id', session()->get('pro_fiscal_year'))->first();
                                        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
                                        if(!$setting){
                                            $setting = $project;
                                        }
                                        ?>
                                        <?php $construction_type[] = $project->construction_type;?>
                                        <tr @if($checkIfThisProgressExist) class="success" @endif>
                                            <td>{!! $setting->budget_topic->budget_topic_num !!} </td>
                                            <td>{!! $project->name !!} <span
                                                        class="text-info">{!! $setting->project_code !!}</span></td>
                                            <td class="bill_exp_td">
                                                {!! Form::text('bill_exp[]',$checkIfThisProgressExist?$checkIfThisProgressExist->bill_exp:null,['class'=>'form-control bill_expenditure','placeholder'=>'Bill Expenditure']) !!}
                                                <?php
                                                $oldExpenses = 0;
                                                if ($project->progresses) {
                                                    foreach ($project->progresses()->where('fy_id', session()->get('pro_fiscal_year'))->where('month_id', '<>', intval(session()->get('month_id_session')))->get() as $progress) {
                                                        $oldExpenses += $progress->bill_exp;
                                                    }
                                                }
                                                ?>
                                                <span data-oldBill="{{ $oldExpenses }}"
                                                      class="text-info pull-right"></span>
                                            </td>
                                            <td>
                                                @if($project->procurement->contract_amount > 0)
                                                    <span style="font-family: 'Fontasy Himali';"
                                                          class="pull-right">{{  number_format($project->procurement->contract_amount*0.05,2) }} </span>
                                                @else
                                                    <span style="font-family: 'Fontasy Himali';"
                                                          class="pull-right">{{  number_format($project->procurement->estimated_amount*0.05,2) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {!! Form::text('cont_exp[]',$checkIfThisProgressExist?$checkIfThisProgressExist->cont_exp:0,['class'=>'form-control cont_exp','placeholder'=>'Contingency Expenditure']) !!}
                                                <span class="text-info pull-right"></span>
                                            </td>
                                            <td>
                                                {!! Form::text('current_physical_progress[]',$checkIfThisProgressExist?$checkIfThisProgressExist->current_physical_progress:null,['class'=>'form-control','placeholder'=>'This Trim/Month s Physical Progress in Percentage']) !!}
                                            </td>
                                            <td>
                                                <?php
                                                $projectRemarks = null;
                                                if ($project->lastProgress) {
                                                    $projectRemarks = $project->lastProgress->project_remarks;
                                                }
                                                ?>
                                                {!! Form::textarea('project_remarks[]',$checkIfThisProgressExist?$checkIfThisProgressExist->project_remarks:$projectRemarks,['class'=>'form-control project_remarks','placeholder'=>'आयोजनाको हालको अवस्था उल्लेख गर्नुहोस','rows'=>'2']) !!}
                                            </td>
                                            <td>
                                                <?php
                                                if ($project->construction_type_id == 1) {
                                                    $storeyAreaUnit = $project->story_area_unite;
                                                    if ($project->story_area_unite % 2 == 0) { // edi 2.5 talla ko bhawan ho bhane, telsai 3 talla samma ko progress track dekhaeko .
                                                        $storeyAreaUnit = $project->story_area_unite + 1;
                                                    }
                                                    $project_track_source = \PMIS\ProgressTrack::whereProgress_type(1)->whereStorey_area($storeyAreaUnit)->pluck('progress', 'id')->toArray();

                                                } else {
                                                    $project_track_source = \PMIS\ProgressTrack::whereProgress_type($project->construction_type_id)->pluck('progress', 'id')->toArray();
                                                }



                                                if (count($project_track_source) == 0) {
                                                    $project_track_source[0] = 'None';
                                                }


                                                $selected = null;
                                                if ($project->progresses) {
                                                    if ($project->progresses->last()) {
                                                        $selected = $project->progresses->last()->pt_id;
                                                    }
                                                }
                                                ?>
                                                {!! Form::select('pt_id[]', $project_track_source, $checkIfThisProgressExist?$checkIfThisProgressExist->pt_id:$selected, ['class'=>'form-control project_status','id'=>'project_status'.$project->id]) !!}
                                            </td>
                                            <td>
                                                {!! Form::select('project_status[]', getProjectStatus(), $project->project_status, ['class'=>'form-control project_status append','id'=>'project_status'.$project->id]) !!}
                                            </td>
                                            <td class="payment">
                                                <?php
                                                $disabled = '';
                                                $disabledHoDate = 'disabled';
                                                if ($project->project_status == 0) {
                                                    $disabled = "disabled";
                                                } elseif ($project->project_status == 2) {
                                                    $disabledHoDate = "";
                                                }
                                                ?>
                                                {!! Form::select('payment_status[]', getPaymentStatus(), $project->payment_status, ['class'=>'form-control payment_status','id'=>'payment_status',$disabled]) !!}
                                            </td>
                                            <td class="date">
                                                {!! Form::text('completed_date[]',$project->completed_date,['class'=>'form-control nepaliDate completed_date','id'=>'date'.$project->id,$disabled,'placeholder'=>'सकीएको मिति','required'=>'required']) !!}
                                                {!! Form::text('handover_date[]',$project->ho_date,['class'=>'form-control nepaliDate hodate','id'=>'hodate'.$project->id,$disabledHoDate,'placeholder'=>'हस्तान्तरण मिति','required'=>'required']) !!}
                                                {!! Form::select('ho_fy[]', $fiscalyears, null, ['class'=>'form-control ficsalyear','disabled'=>'disabled']) !!}

                                                {!! Form::hidden('project_code[]',$project->id) !!}
                                                {!! Form::hidden('fy_id[]',session()->get('pro_fiscal_year')) !!}
                                                {!! Form::hidden('month_id[]',session()->get('month_id_session')) !!}
                                                {!! Form::hidden('last_amendment[]',session()->get('pro_last_amendment')) !!}
                                                {!! Form::hidden('status[]', 1) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($count!=0)
                                        <tr class="panel-footer">
                                            <td>
                                                <button class="btn btn-success" data-loading-text="Saving..."
                                                        autocomplete="off">Save
                                                </button>
                                            </td>
                                            <td colspan="10">
                                                <button class="btn btn-default" type="reset">Reset</button>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </table>
                            {!! Form::close() !!}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <?php $sum = 0;$cont_sum = 0; ?>
                                    @foreach($projects as $project)
                                        <?php
                                        $progresses = $project->progresses()->whereMonthId(intval(session()->get('month_id_session')))->where('fy_id', session()->get('pro_fiscal_year'))->orderBy('month_id', 'desc')->get();
                                        ?>
                                        @if(session()->get('month_id_session')==12)
                                            <?php
                                            $cont_sum += $progresses->first() ? $progresses->first()->cont_exp : 0;
                                            $sum += $progresses->first() ? $progresses->first()->bill_exp : 0;
                                            ?>
                                        @else
                                            @foreach($progresses as $progress)
                                                <?php
                                                $sum += $progress->bill_exp;
                                                ?>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    <h3>
                                        <table class="table table-striped text-center">
                                            @if(session()->get('month_id_session')==12)
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>यस आ. व. को जम्मा खर्च</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>बिल खर्च</td>
                                                    <td>{{ $sum }}</td>
                                                </tr>
                                                <tr>
                                                    <td>कन्टिनजेन्सी खर्च</td>
                                                    <td>{{ $cont_sum }}</td>
                                                </tr>
                                                <tr>
                                                    <td>जम्मा</td>
                                                    <td>{{ $cont_sum+$sum }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>यस चौमासिकको जम्मा खर्च</strong>
                                                    </td>
                                                    <td>{{ $sum }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{{asset('public/admin/js/sweet-alert.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $('.ficsalyear').hide();
        $('document').ready(function () {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

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
            @if($user_info->implementingOffice->isNewTown)
                $form_input += '<br>लागु गर्ने कार्यालय<br>{!! Form::select("implementing_office", $implementing_offices, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}';
            @else
                $form_input += '<br>लागु गर्ने कार्यालय<br>{!! piuOfficesSelectList($implementing_offices_new_update,null) !!}';
            @endif
            $form_input += '<br>महिना<br>{!! Form::select("month_id", $month_name, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}';
            $sweet_input.after('{!! Form::open(["route"=>"set.budget.topic","id"=>"pro_budget_topic_form", "method"=>"get"]) !!}' + $form_input + '{!! Form::close() !!}');
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
                    @if(!$projects->isEmpty())
                    @for($i = 0; $i!=$index; $i++)
            <?php $progress_tracks = \PMIS\ProgressTrack::select('progress')->distinct()->get();?>
                $source{{$i}} = [<?php foreach ($progress_tracks as $progress_track) echo '"' . $progress_track->progress . '",'; ?>];
            var $progress_tracks{{$i}} = $('#pt_id{{$i}}');
            $progress_tracks{{$i}}.tokenfield({
                autocomplete: {
                    source: $source{{$i}},
                    delay: 100
                },
                createTokensOnBlur: true,
                showAutocompleteOnFocus: true
            });

            preventDuplicate($progress_tracks{{$i}});
            @endfor
            @endif
            $('#implementing_office').on('change', function(){
                fill_input($(this).val())
            })
        });

        function fill_input($val) {
            $(".sweet-alert input").val($val);
        }

        $(".project_remarks").focus(function () {
            $(this).select();
        });


        $(".project_status").change(function () {
            $project_status = $(this).val();
            if ($project_status == 2) {
                $(this).parent().parent().find('td.date .completed_date').removeAttr('disabled');
                $(this).parent().parent().find('td.date .hodate').removeAttr('disabled');
                $(this).parent().parent().find('td.payment select').removeAttr('disabled');
            }
            else if ($project_status == 1) {
                $(this).parent().parent().find('td.date .hodate').attr('disabled', 'disabled');
                $(this).parent().parent().find('td.date .completed_date').removeAttr('disabled');
                $(this).parent().parent().find('td.payment select').removeAttr('disabled');
            } else if ($project_status == 0) {
                $(this).parent().parent().find('td.date .hodate').attr('disabled', 'disabled');
                $(this).parent().parent().find('td.date .completed_date').attr('disabled', 'disabled');
                $(this).parent().parent().find('td.date .completed_date').val(null);
                $(this).parent().parent().find('td.payment select').attr('disabled', 'disabled');
            }
        });

        $('.bill_expenditure').focusout((function () {
            var value = parseFloat($(this).val());
            oldval = parseFloat($(this).siblings(":last").attr('data-oldBill'));
            newVal = value + oldval;
            if (!isNaN(newVal)) {
                newVal = newVal.toFixed(3);
                $(this).siblings(":last").first().html(newVal);
            } else {
                $(this).siblings(":last").first().html('');
            }
        }));

        $('.cont_exp').focusout(function () {
            $value = parseFloat($(this).val());
            $bill_expenditure = $(this).parent().parent().find('td.bill_exp_td').find('input').val();
            $sum = parseFloat($value) + parseFloat($bill_expenditure);
            if (!isNaN($sum)) {
                $(this).siblings(":last").first().html($sum);
            } else {
                $(this).siblings(":last").first().html('');
            }

        });

        $('.completed_date').focusout((function () {
            if ($(this).val() == 0 || $(this).val() == '') {
                $(this).val('0000-00-00');
            }
        }));
        $('.hodate').focusout((function () {
            if ($(this).val() == 0 || $(this).val() == '') {
                $(this).val('0000-00-00');
            }
        }));

    </script>

@stop