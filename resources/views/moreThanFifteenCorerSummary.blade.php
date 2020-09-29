@extends('layouts.admin_layout')


@section('headerContent')
@stop

@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                <li><a href="{{route('summary')}}"><i class="fa fa-file-text-0"></i> १५ करोड भन्दा माथिको मात्र</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            {!! Form::open(['route'=>'moreThanFifteenCorerSummary','method'=>'get','id'=>'pro_helper_form']) !!}
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
                    <h4>Summary Report (More than fifteen corer only) - <a style="color:red;" href="{{route('set.destroy.session.summary')}}"> अर्को चौमासिक/मासिक छान्नुहोस्</a></h4>
                    <div id="dvData">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
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
                                <tr>
                                    <th colspan="13" style="text-align: center;">नेपाल सरकार</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: center;">सहरी विकास मन्त्रालय</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: center;">सहरी विकास तथा भवन निर्माण विभाग</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: center;"> {{ $trim }} प्रगति	</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: center;">( १५ करोड माथिका आयोजना मात्र)</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: left;">मन्त्रालय ः शहरी विकास मन्त्रालय</th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: left;">आ.ब. {{ $fy_name }} </th>
                                </tr>
                                <tr>
                                    <th colspan="13" style="text-align: right;">रकम (रु.हजार)</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">क्र.सं.</th>
                                    <th rowspan="2">ब.उ.शि.नं.</th>
                                    <th rowspan="2">आयोजना वा कार्यक्रमको नाम</th>
                                    <th rowspan="2">मुख्य कार्य</th>
                                    <th rowspan="2">कार्य सम्पादन सूचक÷ एकाइ</th>
                                    <th colspan="2">वार्षिक लक्ष्य</th>
                                    <th colspan="2">{{ $trim }}  लक्ष्य</th>
                                    <th colspan="3">प्रगति÷उपलब्धि</th>
                                    <th rowspan="2">कैफियत</th>
                                </tr>
                                <tr>
                                    <th rowspan="">कार्यगत</th>
                                    <th>विनियोजित बजेट</th>
                                    <th>कार्यगत</th>
                                    <th>बजेट</th>
                                    <th>यस महिनाको</th>
                                    <th>यस महिना सम्मको</th>
                                    <th>यस महिनाको खर्च</th>
                                </tr>
                                <tr>
                                    <td>१</td><td>२</td><td>३</td><td>४</td><td>५</td><td>६</td><td>७</td><td>८</td><td>९</td><td>१०</td><td>११</td><td>१२</td><td>१३</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total_project_budget = 0;
                                    $total_yearly_budget = 0;
                                    $total_progress = 0;
                                    $total_programs = 0;
                                    $i = 0;
                                ?>
                                @foreach($budgetTopics as $budgetTopic)
                                    <?php
                                        $i++;
                                        $total_programs += $budgetTopic->projects()->count();
                                    ?>
                                    <tr>
                                        <?php
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
                                        foreach($budgetTopic->projects->sortBy('id') as $project){
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
                                        @foreach($budgetTopic->projects as $index=>$project)
                                            <?php
                                            $project_cost = ($project->procurement->contract_amount > 0)?$project->procurement->contract_amount:$project->procurement->estimated_amount;
                                            $allocation = $project->allocation->where('fy_id',intval(session()->get('pro_fiscal_year')))->sortByDesc('amendment')->first();
                                            //yearly
                                            $total_budget = 0;
                                            if($allocation){
                                                $total_budget = $allocation->total_budget;
                                            }
                                            $yearly_quantity = null;
                                            if($project_cost > 0)
                                                $yearly_quantity = ($total_budget/$project_cost)*100;
                                            $total_yearly_weight += $yearly_weight = ($total_budget/$total_total_budget)*100;
                                            // this trim/month progress
                                            $progresses = $project->progresses()->where('month_id',$month_id)->where('fy_id',intval(session()->get('pro_fiscal_year')))->first();
                                            $upto_progress = $project->progresses()->where('month_id','<=',$month_id)->where('fy_id',intval(session()->get('pro_fiscal_year')));
                                            $trimesters = get_trim($trimester->id);
                                            $trim_budget=0;
                                            if($allocation){
                                                $total_trim_budget += $trim_budget = $allocation->$trimesters;
                                            }
                                            $trim_quantity = null;
                                            if($project_cost > 0)
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
                                        <td>{{ $i }}</td>
                                        <td>{{$budgetTopic->budget_topic_num}}</td>
                                        <td>{{$budgetTopic->budget_head}}</td>
                                        <td></td>
                                        <td>प्र.श.</td>
                                        <td>-</td>
                                        <td>{{ $total_total_budget }}</td>
                                        <td>-</td>
                                        <td>{{ $total_trim_budget }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $total_prog_trim_expense }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>१</td><td>२</td><td>३</td><td>४</td><td>५</td><td>६</td><td>७</td><td>८</td><td>९</td><td>१०</td><td>११</td><td>१२</td><td>१३</td>
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
@stop