@extends('layouts.admin_layout')


@section('headerContent')
@stop

@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                <li><a href="{{route('summary')}}"><i class="fa fa-file-text-0"></i> Summary Report</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            {!! Form::open(['route'=>'summary','method'=>'get','id'=>'pro_helper_form']) !!}
                            <div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    {{--{!! Form::select('budget_topic_id',$budget_topics,$budget_topic_id,['class'=>'form-control', 'id'=>'pro_budget_topic_id']) !!}--}}
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    {{--{!! Form::select('expenditure_topic_id',$expenditure_topics,$expenditure_topic_id,['class'=>'form-control', 'id'=>'pro_expenditure_topic_id']) !!}--}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    {{--{!! Form::select('implementing_office_id',$implementing_offices,$implementing_office_id,['class'=>'form-control','id'=>'pro_implementing_office_id']) !!}--}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    {{--{!! Form::select('division_code',$division_codes,$division_code,['class'=>'form-control pro_submit_form pro_hide_me pro_division_code','disabled'=>'disabled']) !!}--}}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-12">
                    <div id="chartContainer" style="height: 400px; width: 100%;"></div>
                </div>

                <div class="col-md-12 col-lg-12">
                    <h4>Summary Report (According to Budget Topic) - <a style="color:red;" href="{{route('set.destroy.session.summary')}}"> अर्को चौमासिक/मासिक छान्नुहोस्</a></h4>
                    <div id="dvData">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="10">
                                        रा.यो.आ. के.अ.मु. फारम नं.८
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        <?php
                                            if(session()->has('pro_fiscal_year'))
                                                $fy_name = \PMIS\Fiscalyear::whereId(session()->get('pro_fiscal_year'))->first()->fy;
                                            else
                                                $fy_name='';
                                            if(session()->has('month_id_session_summary'))
                                                $trim = \PMIS\Month::whereId(session()->get('month_id_session_summary'))->first()->name;
                                            else
                                                $trim = '';
                                        ?>
                                        पहिलो प्राथमिकता प्राप्त  आयोजना ⁄कार्यक्रमहरुको आ.व         {{ $fy_name }}              को {{ $trim }} को  प्रगति स्थिति, कार्यान्वयनमा देखिएका समस्याहरु र समानार्थ भएका प्रयासहरु
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        १.  मन्त्रालय⁄निकायको नाम:- सहरी विकास मन्त्रालय
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        <?php
                                            $i=0;
                                            $total_project_budget = 0;
                                            $total_yearly_budget = 0;
                                            $total_progress = 0;
                                            $total_programs = 0;
                                            $progress_received = 0;
                                            $above_eighty = 0;
                                            $fifty_to_eighty = 0;
                                            $below_fifty = 0;
                                        ?>
                                @foreach($budgetTopics as $index=>$budgetTopic)
                                    <?php
                                        $i++;
                                        $total_programs += $budgetTopic->projects()->count();
                                        $month_id = $month->id;
                                        $total_project_cost = 0;
                                        $total_total_budget = 0;
                                        $total_yearly_weight = 0;
                                        $total_trim_weight = 0;
                                        $total_trim_budget = 0;
                                        $total_prog_trim_weight = 0;
                                        $total_prog_trim_expense = 0;
                                        $total_upto_prog_trim_weight = 0;
                                        $total_upto_prog_trim_expense = 0;
                                        $prog_trim_weight = 0;
                                        $upto_prog_trim_expense = 0;
                                        foreach($budgetTopic->projects()->where('show_on_running','1')->get() as $project){
                                            if($project->procurement->contract_amount > 0){
                                                $total_project_cost += $project->procurement->contract_amount;
                                            }else{
                                                $total_project_cost += $project->procurement->estimated_amount;
                                            }

                                            $allo = $project->allocation->where('fy_id',intval(session()->get('pro_fiscal_year')))->sortByDesc('amendment')->first();
                                            if($allo){
                                                $total_total_budget += $allo->total_budget;
                                            }
                                        }
                                    ?>
                                    @foreach($budgetTopic->projects()->where('show_on_running','1')->get() as $index=>$project)
                                        <?php
                                        $project_cost = ($project->procurement->contract_amount > 0)?$project->procurement->contract_amount:$project->procurement->estimated_amount;
                                        $allocation = $project->allocation->where('fy_id',intval(session()->get('pro_fiscal_year')))->sortByDesc('amendment')->first();
                                        //yearly
                                        if($allocation){
                                            $total_budget = $allocation->total_budget;
                                        }
                                        $yearly_quantity = ($total_budget/$project_cost)*100;
                                            $total_yearly_weight += $yearly_weight = ($total_budget/$total_total_budget)*100;
                                        // this trim/month progress

                                            $progresses = $project->progresses()->where('month_id',$month_id)->where('fy_id',intval(session()->get('pro_fiscal_year')))->first();
                                        $upto_progress = $project->progresses()->where('month_id','<=',$month_id)->where('fy_id',intval(session()->get('pro_fiscal_year')));
                                        $trimesters = get_trim($trimester->id);
                                        if($allocation){
                                            $total_trim_budget += $trim_budget = $allocation->$trimesters;
                                        }
                                        $trim_quantity = ($trim_budget/$project_cost)*100;
                                        if($trim_quantity>0){
                                            $total_trim_weight += $trim_weight = ($trim_budget/$total_total_budget)*100;
                                        }else{
                                            $total_trim_weight += $trim_weight = 0;
                                        }
                                        $prog_trim_percentage = 0;
                                        $prog_trim_quantity = 0;
                                        $upto_prog_trim_quantity = 0;
                                        $upto_prog_trim_weight = 0;
                                        $upto_prog_trim_expense = 0;
                                        if($progresses){
                                            $prog_trim_percentage = $progresses->current_physical_progress;
                                            $prog_trim_quantity = $trim_quantity*($prog_trim_percentage/100);
                                            if($prog_trim_quantity>0){
                                                $total_prog_trim_weight += $prog_trim_weight = $trim_weight/$trim_quantity*$prog_trim_quantity;
                                            }
                                            else{
                                                $total_prog_trim_weight += $prog_trim_weight = 0;
                                            }
                                            $prog_trim_expense = $progresses->bill_exp + $progresses->cont_exp;
                                            $total_prog_trim_expense += $prog_trim_expense;
                                            $aa = 0;
                                        }
                                        ?>
                                    @endforeach
                                    <?php
                                        if($total_prog_trim_weight > 0){
                                            $physical_progress = ($total_prog_trim_weight/$total_trim_weight)*100;
                                            $progress_received++;
                                            if($physical_progress > 80){
                                                $above_eighty++;
                                            }elseif($physical_progress > 50 && $physical_progress < 80){
                                                $fifty_to_eighty++;
                                            }else{
                                                $below_fifty++;
                                            }
                                        }
                                    ?>
                                @endforeach
                                        २. पहिलो प्राथमिकता प्राप्त   आयोजना⁄कार्यक्रमहरुको जम्मा सङ्ख्या:  &nbsp; &nbsp; &nbsp; {{ $i }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        ३. सोमध्ये प्रगति विवरण प्राप्त आयोजना ⁄कार्यक्रमहरुको सङ्ख्या:  &nbsp; &nbsp; &nbsp; {{ $progress_received }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        ४. ८० प्रतिशत वा सोभन्दा माथि प्रगति हासिल गरेका आयोजना ⁄कार्यक्रमहरुको सङ्ख्या:  &nbsp; &nbsp; &nbsp; {{ $above_eighty }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        ५. ५० प्रतिशतदेखि ७९.९९ प्रतिशतसम्म प्रगति हासिल गरेका आयोजना ⁄कार्यक्रमहरुको सङ्ख्या:  &nbsp; &nbsp; &nbsp; {{ $fifty_to_eighty }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        ६. ५० प्रतिशतभन्दा कम प्रगति  हासिल गरेका आयोजना ⁄कार्यक्रमहरुको सङ्ख्या:  &nbsp; &nbsp; &nbsp; {{ $below_fifty }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">
                                        ७. यस अवधिमा चौमासिक लक्ष्य नभएका आयोजना ⁄कार्यक्रमहरुको सङ्ख्या:  &nbsp; &nbsp; &nbsp; -
                                    </th>
                                </tr>
                                <tr>
                                    <th rowspan="2">क्र.स.</th>
                                    <th rowspan="2">बजेट उपशीर्षक न.</th>
                                    <th rowspan="2">आयोजना/कार्यक्रमको नाम</th>
                                    <th colspan="2">{{ $trim }} प्रगति स्थिति (%)</th>
                                    <th rowspan="2">यस अवधिसम्म हासिल भएको मुख्य मुख्य उपलब्धिहरू</th>
                                    <th rowspan="2">कार्यान्वयनमा देखापरेका प्रमुख समस्याहरू</th>
                                    <th rowspan="2">समस्या समाधानार्थ भएका प्रयासहरू</th>
                                    <th rowspan="2">समस्या समाधानार्थ सुझावहरु</th>
                                    <th rowspan="2">राविससस बैठकमा प्रस्तुत गर्नुपर्ने समस्याहरू</th>
                                </tr>
                                <tr>
                                    <th>भौतिक</th>
                                    <th>वित्तीय</th>
                                </tr>
                                <tr>
                                    <td>१</td><td>२</td><td>३</td><td>४</td><td>५</td><td>६</td><td>७</td><td>८</td><td>९</td><td>१०</td>
                                </tr>
                                <!-- <tr>
                                    <th>बजेट शिर्षक</th>
                                    <th>कार्यक्रम संख्या</th>
                                    <th>कूल बजेट</th>
                                    <th>बार्षिक बजेट</th>
                                    <th>प्रगति</th>
                                </tr> -->
                            </thead>
                            <tbody>
                                <?php
                                    $total_project_budget = 0;
                                    $total_yearly_budget = 0;
                                    $total_progress = 0;
                                    $total_programs = 0;
                                    $i = 0;
                                ?>
                                @foreach($budgetTopics as $index=>$budgetTopic)
                                    <?php $i++; $total_programs += $budgetTopic->projects()->count();
                                        $bt_chart['budget_topics'][$index] = $budgetTopic->budget_topic_num;
                                    ?>
                                    <tr>
                                        <?php
                                        $month_id = $month->id;
                                        ?>
                                        <?php
                                            $i = 0;
                                            $total_project_cost = 0;
                                            $total_total_budget = 0;
                                            $total_yearly_weight = 0;
                                            $total_trim_weight = 0;
                                            $total_trim_budget = 0;
                                            $total_prog_trim_weight = 0;
                                            $total_prog_trim_expense = 0;
                                            $total_upto_prog_trim_weight = 0;
                                            $total_upto_prog_trim_expense = 0;
                                            $prog_trim_weight = 0;
                                            $upto_prog_trim_expense = 0;
                                            foreach($budgetTopic->projects()->where('show_on_running','1')->get() as $project){
                                                if($project->procurement->contract_amount > 0){
                                                    $total_project_cost += $project->procurement->contract_amount;
                                                }else{
                                                    $total_project_cost += $project->procurement->estimated_amount;
                                                }
                                                $allo = $project->allocation->where('fy_id',intval(session()->get('pro_fiscal_year')))->sortByDesc('amendment')->first();
                                                if($allo){
                                                    $total_total_budget += $allo->total_budget;
                                                }
                                            }
                                        ?>
                                        @foreach($budgetTopic->projects()->where('show_on_running','1')->get() as $index=>$project)
                                            <?php
                                            $i++;
                                            $project_cost = ($project->procurement->contract_amount > 0)?$project->procurement->contract_amount:$project->procurement->estimated_amount;
                                            $allocation = $project->allocation->where('fy_id',intval(session()->get('pro_fiscal_year')))->sortByDesc('amendment')->first();
                                            //yearly
                                            if($allocation){
                                                $total_budget = $allocation->total_budget;
                                            }

                                            $yearly_quantity = ($total_budget/$project_cost)*100;
                                            $total_yearly_weight += $yearly_weight = ($total_budget/$total_total_budget)*100;
                                            // this trim/month progress
                                            $progresses = $project->progresses()->where('month_id',$month_id)->where('fy_id',intval(session()->get('pro_fiscal_year')))->first();
                                            $upto_progress = $project->progresses()->where('month_id','<=',$month_id)->where('fy_id',intval(session()->get('pro_fiscal_year')));
                                            $trimesters = get_trim($trimester->id);
                                            if($allocation){
                                                $total_trim_budget += $trim_budget = $allocation->$trimesters;
                                            }
                                            $trim_quantity = ($trim_budget/$project_cost)*100;
                                            if($trim_quantity>0){
                                                $total_trim_weight += $trim_weight = ($trim_budget/$total_total_budget)*100;
                                            }else{
                                                $total_trim_weight += $trim_weight = 0;
                                            }
                                            $prog_trim_percentage = 0;
                                            $prog_trim_quantity = 0;
                                            $upto_prog_trim_quantity = 0;
                                            $upto_prog_trim_weight = 0;
                                            $upto_prog_trim_expense = 0;
                                            if($progresses){
                                                $prog_trim_percentage = $progresses->current_physical_progress;
                                                $prog_trim_quantity = $trim_quantity*($prog_trim_percentage/100);
                                                if($prog_trim_quantity>0){
                                                    $total_prog_trim_weight += $prog_trim_weight = $trim_weight/$trim_quantity*$prog_trim_quantity;
                                                }
                                                else{
                                                    $total_prog_trim_weight += $prog_trim_weight = 0;
                                                }
                                                $prog_trim_expense = $progresses->bill_exp + $progresses->cont_exp;
                                                $total_prog_trim_expense += $prog_trim_expense;
                                                $aa = 0;
                                            }
                                            ?>
                                        @endforeach
                                        <?php
                                            $physical_progress = number_format(($total_prog_trim_weight/$total_trim_weight)*100,2);
                                            $fininacial_progress = number_format(($total_prog_trim_expense/$total_trim_budget)*100,2);
                                            $bt_chart['physical_progress'][$index] = $physical_progress;
                                            $bt_chart['financial_progress'][$index] = $fininacial_progress;

                                        ?>
                                        <td>{{$i}}</td>
                                        <td>{{$budgetTopic->budget_topic_num}}</td>
                                        <td>{{$budgetTopic->budget_head}}</td>
                                        <td>
                                            @if($total_trim_weight > 0 && $total_prog_trim_weight > 0)
                                                {{number_format(($total_prog_trim_weight/$total_trim_weight)*100,2)}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($total_trim_budget > 0 && $total_prog_trim_expense > 0)
                                                {{number_format(($total_prog_trim_expense/$total_trim_budget)*100,2)}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10">नोट ः– यो फारम विभाग, केन्द्रिय निकाय वा मन्त्रालयले तेश्रो चौमासिक र बार्षिक अवधिको लागि छुट्टा छुट्टै तयार गर्नु पर्ने</td>
                                </tr>
                                <tr>
                                    <td colspan="10">सालवसाली आयोजनाको हकमा महल ४, ५ र ६ भर्नुपर्दैन ।</td>
                                </tr>
                                <tr>
                                    <td colspan="10">मविसस समितिको पछिल्लो वैठकभन्दा अघिको बैठकका निर्णहरूको कार्यान्वयन स्थितिः</td>
                                </tr>
                                <tr>
                                    <td colspan="10">१</td>
                                </tr>
                                <tr>
                                    <td colspan="10">२</td>
                                </tr>
                                <tr>
                                    <td colspan="10">मविसस समितिको वैठकमा भएका प्रमुख निर्णयहरूः</td>
                                </tr>
                                <tr>
                                    <td colspan="10">१</td>
                                </tr>
                                <tr>
                                    <td colspan="10">२</td>
                                </tr>
                                <tr>
                                    <td colspan="3">तयार गर्नेको नाम, पद र दस्तखतः– </td>
                                    <td colspan="4">अनुगमन महाशाखा÷शाखा प्रमुखको नाम, पद र दस्तखत ः– </td>
                                    <td colspan="3">प्रमाणित गर्नेको नाम, पद र दस्तखतः–</td>
                                </tr>
                                <tr>
                                    <td colspan="3">मिति ः– </td>
                                    <td colspan="4">मिति ः– </td>
                                    <td colspan="3">मिति ः– </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="panel panel-default">
                <div class="panel-footer">
                    <button id="btnExport" class="btn btn-success" data-loading-text="Exporting..." autocomplete="off">Export Report</button>
                    <button id="btnPrint" class="btn btn-success" data-loading-text="Printing..." autocomplete="off">Print Report</button>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/jquery.table2excel.js')}}"></script>
    <script src="{{ asset('public/js/jquery.printElement.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            @if(!session()->has('month_id_session_summary'))
                swal({
                        title: "Choose Trimester / Month",
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
            $sweet_input.after('{!! Form::open(["route"=>"set.month.id.summary","id"=>"pro_month_id_form", "method"=>"get"]) !!}{!! Form::select("month_id", $months, null, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}{!! Form::close() !!}');
            @endif

            $('#btnExport').click(function(){
                $("#dvData").table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "myFileName",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });
            })
            $('#btnPrint').click(function(){
                $('#dvData').show().printElement();
            })
        });
    </script>

    <script type="text/javascript">
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: ""
                },
                axisX: {
                    interval: 10
                },
                data: [{
                    type: "column",
                    name: "Physical Progress",
                    showInLegend: true,
                    dataPoints: [<?php $index=0;?>
                        @foreach($bt_chart['physical_progress'] as $physical_progress)
                            {!!  '{ x: '.(($index+1)*10).',label: "'.$bt_chart['budget_topics'][$index++].'", y: '.$physical_progress.', indexLabel: "'.$physical_progress.'%" },'  !!}
                        @endforeach
                    ]
                }, {
                    type: "column",
                    name: "Finincial Progress",
                    showInLegend: true,
                    dataPoints: [
                            <?php $index = 0;?>
                        @foreach($bt_chart['financial_progress'] as $physical_progress)
                            {!! '{ x: '.(($index+1)*10).', y: '.$physical_progress.' , indexLabel: "'.$physical_progress.'%" },'  !!}
                        @endforeach
                    ]
                }]
            });
            chart.render();
        }
    </script>
    <script src="{{ asset('public/plugin/canvasjs-1.8.1-beta2/canvasjs.min.js') }}" type="text/javascript"></script>
@stop