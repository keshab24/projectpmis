@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a class="btn btn-info showToolTip" title="Show Progresses" href="{{route('progress.index')}}"><i class="glyphicon glyphicon-eye-open"></i> Progresses</a></li>
                    <li class="active"><a href="{{route('progress.create')}}"><i class="fa fa-plus"></i> Add Progresses</a></li>
                    {{--<li class="active"><a href="{{route('set.destroy.session')}}"><i class="fa fa-exchange" style="color: #ff0000;"></i>Change Session</a></li>--}}
                    {{--@if(session()->has('month_id_session')))--}}
                    {{--<li class="active">ब.ऊ.शी.न. {{ $budget_name_session[0] }}, ख.शी.न. {{ $expenditure_name_session[0] }} को {{ $month_name_session[0] }} महिनाको प्रगति चढाउनुहोस</li>--}}
                    {{--@endif--}}
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
                                        <th width="20%">
                                            अायाेजना
                                        </th>
                                        <th width="7%">
                                            बिल खर्च (रु हजारमा)
                                        </th>
                                        <th width="5%">
                                            कन्टिन्जेन्सि खर्च
                                        </th>
                                        <th width="8%">
                                            हालको भौतिक प्रगति मात्र (%)
                                        </th>
                                        <th width="15%">
                                            हालको अवस्था
                                        </th>
                                        <th width="15%">
                                            भौतिक प्रगति छान्नुहोस्
                                        </th>
                                        <th width="8%">
                                            Project Status
                                        </th>
                                        <th width="8%">
                                            Payment Status
                                        </th>
                                        <th width="15%">
                                            Wc/Ho Date
                                        </th>
                                    </tr>
                                    @if($projects->isEmpty())
                                        <tr>
                                            <td colspan="6">
                                                <div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i> Please add some Progress first!!</div>
                                            </td>
                                        </tr>
                                    @else
                                        <tr @if(isset($_GET['trashes'])) class="danger" @endif>
                                            {!!Form::model($progress,['route'=>['progress.update',$progress->id],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                                            <td>{!! $project->budget_topic->budget_topic_num !!}</td>
                                            <td>{!! $project->name !!}</td>
                                            <td class="bill_exp_td">
                                                {!! Form::text('bill_exp',null,['class'=>'form-control bill_expenditure','placeholder'=>'Bill Expenditure','autofocus']) !!}
                                                <?php
                                                $oldExpenses=0;
                                                if($project->progresses){
                                                    foreach ($project->progresses()->where('fy_id',session()->get('pro_fiscal_year'))->get() as $progress){
                                                        $oldExpenses+=$progress->bill_exp;
                                                        $last=$progress->bill_exp;
                                                    }
                                                    $oldExpenses=$oldExpenses-$last;
                                                }
                                                ?>
                                                <span data-oldBill="{{ $oldExpenses }}" class="text-info pull-right">{{$oldExpenses}}</span>
                                            </td>
                                            <td>
                                                {!! Form::text('cont_exp',null,['class'=>'form-control cont_exp','placeholder'=>'Contingency Expenditure']) !!}
                                                <span class="text-info pull-right"></span>
                                            </td>
                                            <td>
                                                {!! Form::text('current_physical_progress',null,['class'=>'form-control','placeholder'=>'This Trim/Month s Physical Progress in Percentage']) !!}
                                            </td>
                                            <td>
                                                {!! Form::textarea('project_remarks',null,['class'=>'form-control','rows'=>'1','placeholder'=>'आयोजनाको हालको अवस्था उल्लेख गर्नुहोस']) !!}
                                            </td>
                                            <td>
                                                <?php
                                                if($project->construction_type_id ==1){
                                                    $storeyAreaUnit=$project->story_area_unite;
                                                    if($project->story_area_unite%2==0){ // edi 2.5 talla ko bhawan ho bhane, telsai 3 talla samma ko progress track dekhaeko .
                                                        $storeyAreaUnit=$project->story_area_unite+1;
                                                    }
                                                    $project_track_source = \PMIS\ProgressTrack::whereProgress_type(1)->whereStorey_area($storeyAreaUnit)->pluck('progress','id')->toArray();

                                                }else{
                                                    $project_track_source = \PMIS\ProgressTrack::whereProgress_type($project->construction_type_id)->pluck('progress','id')->toArray();
                                                }
                                                ?>
                                                {!! Form::select('pt_id', $project_track_source, $progress->pt_id, ['class'=>'form-control pt_id','id'=>'pt_id'.$project->id]) !!}
                                            </td>
                                            {!! Form::hidden('project_code',$project->id) !!}
                                            {!! Form::hidden('last_amendment',session()->get('pro_last_amendment')) !!}
                                            {!! Form::hidden('status','1') !!}
                                            <td>
                                                {!! Form::select('project_status', getProjectStatus(), $project->project_status, ['class'=>'form-control project_status','id'=>'project_status']) !!}
                                            </td>
                                            <td class="payment">
                                                {!! Form::select('payment_status', getPaymentStatus(), $project->payment_status, ['class'=>'form-control payment_status','id'=>'payment_status']) !!}
                                            </td>
                                            <td class="date">
                                                {!! Form::text('completed_date',$project->completed_date,['class'=>'form-control nepaliDate completed_date','id'=>'completed_date','placeholder'=>'सकीएको मिति','required'=>'required']) !!}
                                                {!! Form::text('handover_date',$project->ho_date,['class'=>'form-control nepaliDate hodate','id'=>'handover_date','placeholder'=>'हस्तान्तरण मिति','required'=>'required']) !!}
                                                {{--{!! Form::select('ho_fy[]', $fiscalyears, null, ['class'=>'form-control ficsalyear','disabled'=>'disabled']) !!}--}}
                                            </td>
                                            <div class="panel panel-default">
                                                <div class="panel-footer">
                                                    <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save</button>
                                                    <button class="btn btn-default" type="reset">Reset</button>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                            {{--{!!Form::open(['route'=>['project.status.change', $project->id],'method'=>'put','onsubmit'=>"return confirm('Sure want to change project status?')"])!!}--}}
                                            {{--<td>--}}
                                                {{--@if($project->project_status == 0)--}}
                                                    {{--<button type="submit" title="Completed/ No Payment" class="label label-warning">Running</button>--}}
                                                {{--@elseif($project->project_status == 1)--}}
                                                    {{--<button type="submit" title="Completed/Payment" class="label label-info">Completed/ No Payment</button>--}}
                                                {{--@else--}}
                                                    {{--<button type="submit" title="Running" class="label label-info">Completed/ Payment</button>--}}
                                                {{--@endif--}}
                                            {{--</td>--}}
                                            {{--{!!  Form::close() !!}--}}
                                        </tr>
                                    @endif
                                </table>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-sm-6">
                                {!! massAction('','local','Procurement') !!}
                            </div>
                        </div>
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
    <script type="text/javascript">
        $('document').ready(function () {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            var project_status = $("#project_status").val();
            if (project_status == 0) {
                $('#payment_status').attr("disabled",'disabled');
                $('#handover_date').attr("disabled",'disabled');
                $('#completed_date').attr("disabled",'disabled');
            }else if(project_status == 1){
                $('#handover_date').attr("disabled",'disabled');
            }
            $("#project_status").change(function () {
                project_status = $("#project_status").val();
                if(project_status== 2){
                    $(this).parent().parent().find('td.date .completed_date').removeAttr('disabled');
                    $(this).parent().parent().find('td.date .hodate').removeAttr('disabled');
                    $(this).parent().parent().find('td.payment select').removeAttr('disabled');
                }
                else if(project_status ==1){
                    $(this).parent().parent().find('td.date .hodate').attr('disabled','disabled');
                    $(this).parent().parent().find('td.date .completed_date').removeAttr('disabled');
                    $(this).parent().parent().find('td.payment select').removeAttr('disabled');
                }else if(project_status ==0){
                    $(this).parent().parent().find('td.date .hodate').attr('disabled','disabled');
                    $(this).parent().parent().find('td.date .completed_date').attr('disabled','disabled');
                    $(this).parent().parent().find('td.date .completed_date').val(null);
                    $(this).parent().parent().find('td.payment select').attr('disabled','disabled');
                }
            });

            $('.completed_date').focusout((function(){
                var value=$(this).val();
                if($(this).val()==0 || $(this).val()==''){
                    $(this).val('0000-00-00');
                }
            }));
            $('.hodate').focusout((function(){
                var value=$(this).val();
                if($(this).val()==0 || $(this).val()==''){
                    $(this).val('0000-00-00');
                }
            }));

        });
        $('.bill_expenditure').focusout((function(){
            var value=parseFloat($(this).val());
            var oldval=''
            var newVal=''
            oldval= parseFloat($(this).siblings(":last").attr('data-oldBill'));
            newVal=value + oldval;
            $(this).siblings(":last").first().html(newVal.toFixed(3));
        }));
        $('.cont_exp').focusout(function(){
            $value=parseFloat($(this).val());
            $bill_expenditure=$(this).parent().parent().find('td.bill_exp_td').find('input').val();
            $sum=parseFloat($value)+parseFloat($bill_expenditure);
            if(!isNaN($sum)){
                $(this).siblings(":last").first().html($sum);
            }else{
                $(this).siblings(":last").first().html('');
            }

        });

    </script>
@stop