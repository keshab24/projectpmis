@extends('layouts.admin_layout')

@section('headerContent')
@stop

@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-2 col-md-2 col-sm-2">

                    </div>
                    <div class="col-md-5 col-lg-5 col-sm-5">

                    </div>
                    <div class="col-md-5 col-lg-5 col-sm-5">

                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-md-12 col-lg-12">
                    <div class="table-responsive pro_hide_y centered">
                        @if($projects)
                        <div id="dvData">

                            <table class="table">
                                <tr>
                                    <td colspan="3" align="center">
                                        <h3>बार्षिक कार्यक्रम</h3>
                                        <h4>(बजेट तर्जुमसंग सम्बन्धित आ.प्र. नियम २० (१) बमोजिमको फारम)</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="table">
                                            <tr>
                                                <td>१. </td>
                                                <td> आ.ब. : {{\PMIS\Fiscalyear::whereId(session()->get('pro_fiscal_year'))->first()->fy}}</td>
                                            </tr>
                                            <tr>
                                                <td>२. </td>
                                                <td> बजेट उपशीर्षक न.: {{\PMIS\BudgetTopic::whereId($budget_topic_id)->first()->budget_topic_num }}</td>
                                            </tr>
                                            <tr>
                                                <td>३. </td>
                                                <td> मन्त्रालय : सहरी बिकास मन्त्रालय</td>
                                            </tr>
                                            <tr>
                                                <td>४. </td>
                                                <td> बिभाग/संस्था: शहरी विकास तथा भवन निर्माण बिभाग </td>
                                            </tr>
                                            <tr>
                                                <td>५. </td>
                                                <td> कार्यक्रम/आयोजनाको नाम :{{\PMIS\BudgetTopic::whereId($budget_topic_id)->first()->budget_head }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="table">
                                                        <tr>
                                                            <td>६. स्थान</td>
                                                            <td>
                                                                जिल्ला <br>
                                                                गा.वि.स/न.पा. वडा नम्बर<br>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>७. </td>
                                                <td> आयोजना शुरु भएको मिति: २०६५/६६</td>
                                            </tr>
                                            <tr>
                                                <td>८. </td>
                                                <td> आयोजना पुरा हुने मिति: {{\PMIS\Fiscalyear::whereId(session()->get('pro_fiscal_year'))->first()->fy}}</td>
                                            </tr>
                                            <tr>
                                                <td>९. </td>
                                                <td> आयोजना/कार्यालय प्रमूखको नाम : रमेश प्रसाद सिंह</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <?php
                                        $total_activity_full_weight = 0;
                                        $total_expense_till_last_fy_weight = 0;
                                        $total_yearly_weight = 0;
                                        $total_first_trim_weight = 0;
                                        $total_second_trim_weight = 0;
                                        $total_third_trim_weight = 0;
                                        $total_first_trim_budget = 0;
                                        $total_second_trim_budget = 0;
                                        $total_third_trim_budget = 0;
                                        $total_project_cost = 0;
                                        $total_total_budget = 0;
                                        $total_gon_budget = 0;
                                        $total_loan_budget = 0;
                                        $total_grant_budget = 0;
                                        $total_expense_till_last_fy = 0;
                                        foreach($projects->sortBy('id') as $index=>$project){
                                            if($project->allocation->sortByDesc('amendment')->first()->total_budget > 0){
                                                $allo = $project->allocation->where('fy_id',intval(session()->get('pro_fiscal_year')))->sortByDesc('amendment')->first();
                                                $total_project_cost= $project->projectCost();
                                                if($allo){
                                                    $total_total_budget += $allo->total_budget;
                                                    $total_gon_budget += $allo->gon;
                                                    $total_loan_budget += $allo->loan;
                                                    $total_grant_budget += $allo->grants;
                                                    $total_first_trim_budget += $allo->first_trim;
                                                    $total_second_trim_budget += $allo->second_trim;
                                                    $total_third_trim_budget += $allo->third_trim;
                                                    $progresses = $project->progresses();
                                                    $total_expense_till_last_fy += $progresses->where('fy_id','<',session()->get('pro_fiscal_year'))->sum('bill_exp') + $progresses->where('fy_id','<',session()->get('pro_fiscal_year'))->sum('cont_exp');
                                                }
                                            }
                                        }
                                            //dd($total_total_budget);
                                        $i=0;
                                        ?>
                                        <table class="table">
                                            <tr>
                                                <td>१०. </td>
                                                <td> बार्षिक बजेट :{{ number_format($total_total_budget,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="table">
                                                        <tr>
                                                            <td>क) आन्तरिक</td>
                                                            <td>
                                                                १) नेपाल सरकार : {{ number_format($total_gon_budget,2) }}<br>
                                                                २) स्थानीय निकाय/संस्था <br>
                                                                ३) जनसहभागिता <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>ख) बैदेशिक</td>
                                                            <td>
                                                                १) ऋण :{{ number_format($total_loan_budget,2) }} <br>
                                                                २) अनुदान : {{ number_format($total_grant_budget,2) }}<br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">ग) सटही दर :</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">घ) दातृ संस्था :</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table">
                                            <tr>
                                                <td>११. </td>
                                                <td> आयोजनाको कूल लागत (रु.) : {{ number_format($total_project_cost,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="table">
                                                        <tr>
                                                            <td>क) आन्तरिक</td>
                                                            <td>
                                                                १) नेपाल सरकार <br>
                                                                २) स्थानीय निकाय/संस्था <br>
                                                                ३) जनसहभागिता <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>ख) बैदेशिक</td>
                                                            <td>
                                                                १) ऋण <br>
                                                                २) अनुदान <br>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="table">
                                            <tr>
                                                <td>१२. </td>
                                                <td> गत आ.ब. सम्मको खर्च रु. (सोझै भुक्तानी र बस्तुगत समेत): {{number_format($total_expense_till_last_fy,2) }} </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="table">
                                                        <tr>
                                                            <td>क) आन्तरिक</td>
                                                            <td>
                                                                १) नेपाल सरकार  <br>
                                                                २) स्थानीय निकाय/संस्था <br>
                                                                ३) जनसहभागिता <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>ख) बैदेशिक</td>
                                                            <td>
                                                                १) ऋण <br>
                                                                २) अनुदान <br>
                                                                <i class="pull-right">(रकम रु. हजारमा)</i>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="puff-right" colspan="3" align="center">
                                        <table class="table table-hover" border="1">
                                            <thead>
                                            <tr>
                                                <th rowspan="3">क्र.स.</th>
                                                @if(isset($division_code))
                                                    <th rowspan="3" colspan="2">डी का</th>
                                                @else
                                                    <th rowspan="3" colspan="2">निकाय/कार्यालय</th>
                                                @endif
                                                <th rowspan="3">क्रियाकलाप (Activity) विवरण</th>
                                                <th rowspan="3">एकाई</th>
                                                <th rowspan="2" colspan="3">आयोजनाको कूल क्रियाकलापको</th>
                                                <th rowspan="2" colspan="3">सम्पूर्ण कार्यमध्य गत आ. ब. {{ \PMIS\Fiscalyear::whereId(session()->get('pro_fiscal_year')-1)->first()->fy}} सम्मको</th>
                                                <th colspan="12">{{\PMIS\Fiscalyear::whereId(session()->get('pro_fiscal_year'))->first()->fy}} आ.ब.को बार्षिक लक्ष्य</th>
                                                <th rowspan="3">कैफियत</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3">बार्षिक</th>
                                                <th colspan="3">प्रथम चौमासिक</th>
                                                <th colspan="3">दोश्रो चौमासिक</th>
                                                <th colspan="3">तेस्रो चौमासिक</th>
                                            </tr>
                                            <tr>
                                                <th>परिमाण</th>
                                                <th>लागत</th>
                                                <th>भार</th>
                                                <th>सम्पन्न परिमाण</th>
                                                <th>खर्च</th>
                                                <th>भारित प्रगति</th>
                                                <th>परिमाण</th>
                                                <th>भार</th>
                                                <th>बजेट</th>
                                                <th>परिमाण</th>
                                                <th>भार</th>
                                                <th>बजेट</th>
                                                <th>परिमाण</th>
                                                <th>भार</th>
                                                <th>बजेट</th>
                                                <th>परिमाण</th>
                                                <th>भार</th>
                                                <th>बजेट</th>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>१</td>
                                                <td>२</td>
                                                <td>३</td>
                                                <td>४</td>
                                                <td>५</td>
                                                <td>६</td>
                                                <td>७</td>
                                                <td>८</td>
                                                <td>९</td>
                                                <td>१०</td>
                                                <td>११</td>
                                                <td>१२</td>
                                                <td>१३</td>
                                                <td>१४</td>
                                                <td>१५</td>
                                                <td>१६</td>
                                                <td>१७</td>
                                                <td>१८</td>
                                                <td>१९</td>
                                                <td>२०</td>
                                                <td>२१</td>
                                                <td>२२</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $division_old = '';
                                                $implementing_office_old = '';
                                                $division_id_old = '';
                                                $class = '';
                                            ?>
                                            @foreach($projects as $project)
                                                @if($project->allocation->sortByDesc('amendment')->first()->total_budget > 0)
                                                    <?php
                                                    $project_cost = $project->projectCost();
                                                        $allocation = $project->allocation->where('fy_id',intval(session()->get('pro_fiscal_year')))->sortByDesc('amendment')->first();
                                                        $total_activity_full_weight += $activity_full_weight = $project_cost/$total_project_cost*100;
                                                        $activity_full_weight = $project_cost/$total_project_cost;

                                                        $progresses = $project->progresses();
                                                        $expense_till_last_fy = $progresses->where('fy_id','<',session()->get('pro_fiscal_year'))->sum('bill_exp') + $progresses->where('fy_id','<',session()->get('pro_fiscal_year'))->sum('cont_exp');
                                                        if($project_cost !=0){
                                                            $expense_till_last_fy_quantity = $expense_till_last_fy/$project_cost*100;
                                                            $total_expense_till_last_fy_weight += $expense_till_last_fy_weight = $expense_till_last_fy_quantity/$project->quantity*$activity_full_weight;
                                                            if(!$allocation){ ?>
                                                                <tr class="danger">
                                                                    <td>
                                                                        {{++$i}}
                                                                    </td>
                                                                    <td colspan="2">
                                                                        "
                                                                    </td>
                                                                    <td>
                                                                        {{$project->name}}
                                                                        <br>
                                                                        {{ $project->project_code,4 }}
                                                                    </td>
                                                                    <td colspan="20">
                                                                        अलोकेसन को डाटा हल्नुहोस
                                                                    </td>
                                                                </tr>
                                                            <?php continue;
                                                                }
                                                            try{
                                                                $yearly_quantity = $allocation->total_budget/$project_cost*$project->quantity;

                                                                $yearly_weight = $allocation->total_budget/$total_total_budget*100;
                                                                $total_yearly_weight += $yearly_weight;


                                                                $first_trim_quantity = $allocation->first_trim/$project_cost*100;
                                                                // $total_first_trim_weight += $first_trim_weight = $allocation->first_trim/$total_total_budget*100;
                                                                $total_first_trim_weight += $first_trim_weight = $first_trim_quantity/$yearly_quantity*$yearly_weight;
                                                                $second_trim_quantity = $allocation->second_trim/$project_cost*100;
                                                                $second_trim_weight = $allocation->second_trim/$total_total_budget*100;
                                                                $total_second_trim_weight += $second_trim_weight = $allocation->second_trim/$total_total_budget*100;
                                                                $third_trim_quantity = $allocation->third_trim/$project_cost*100;
                                                                $total_third_trim_weight += $third_trim_weight = $allocation->third_trim/$total_total_budget*100;
                                                            }catch (Exception $e){ ?>
                                                                <tr class="danger">
                                                                    <td>
                                                                        {{++$i}}
                                                                     </td>
                                                                <td colspan="2">
                                                                    "
                                                                </td>
                                                                <td>
                                                                    {{$project->name}}
                                                                    <br>
                                                                    {{ $project->project_code,4 }}
                                                                </td>
                                                                <td colspan="20">
                                                                    डाटामा गडबडी
                                                                </td>
                                                                </tr>
                                                                <?php
                                                                    continue;
                                                                }

                                                        }else{
                                                            try{

                                                                $expense_till_last_fy_quantity = 0;
                                                                $total_expense_till_last_fy_weight += $expense_till_last_fy_weight = $expense_till_last_fy_quantity/$project->quantity*$activity_full_weight;
                                                                $yearly_quantity = 0;

                                                                $yearly_weight = $allocation->total_budget/$total_total_budget*100;
                                                                $total_yearly_weight += $yearly_weight;


                                                                $first_trim_quantity = 0;
                                                                //$total_first_trim_weight += $first_trim_weight = $allocation->first_trim/$total_total_budget*100;
                                                                $total_first_trim_weight += $first_trim_weight = $first_trim_quantity/$yearly_quantity*$yearly_weight;
                                                                $second_trim_quantity = 0;
                                                                $second_trim_weight = $allocation->second_trim/$total_total_budget*100;
                                                                $total_second_trim_weight += $second_trim_weight = $allocation->second_trim/$total_total_budget*100;
                                                                $third_trim_quantity = 0;
                                                                $total_third_trim_weight += $third_trim_weight = $allocation->third_trim/$total_total_budget*100;
                                                                }catch (Exception $e){ ?>
                                                                    <tr class="danger">
                                                                        <td>
                                                                            {{++$i}}
                                                                        </td>
                                                                        <td colspan="2">
                                                                            "
                                                                        </td>
                                                                        <td>
                                                                            {{$project->name}}
                                                                            <br>
                                                                            {{ $project->project_code,4 }}
                                                                        </td>
                                                                        <td colspan="20">
                                                                            डाटामा गडबडी
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    continue;
                                                                    }
                                                        }

                                                    // formatting setting
                                                    if($implementing_office_old == '') $implementing_office_old = $project->implementing_office_id;
                                                    if($implementing_office_old == $project->implementing_office_id){
                                                        if($project->implementing_office->slug =='division'){
                                                            if($division_id_old == '') $division_id_old = $project->division_code;
                                                            if($division_id_old != $project->division_code){
                                                                toggleClass($class);
                                                            }
                                                            $division_id_old = $project->division_code;
                                                        }
                                                    }else{
                                                        toggleClass($class);
                                                    }
                                                    ?>
                                                    <tr class="{{$class}}">
                                                        <td><!-- 1 -->
                                                            {{++$i}}
                                                        </td>
                                                        @if(isset($division_code))
                                                            @if($division_old == '' || $division_old != $project->division->name)
                                                                <th colspan="2">
                                                                    {{str_replace('डिभिजन कार्यालय,','',$project->division->name)}}
                                                                </th>
                                                            @else
                                                                <th colspan="2" class="text-center">
                                                                    "
                                                                </th>
                                                            @endif
                                                        @else
                                                            @if($project->implementing_office->slug =='division')
                                                                @if(!isset($project_code)) <th>डी का</th> @endif
                                                                @if($division_old == '' || $division_old != $project->division->name)
                                                                    <th @if(isset($project_code)) colspan="2" @endif>
                                                                        {{str_replace('डिभिजन कार्यालय,','',$project->division->name)}}
                                                                    </th>
                                                                @else
                                                                    <th class="text-center" @if(isset($project_code)) colspan="2" @endif>
                                                                        "
                                                                    </th>
                                                                @endif
                                                                <?php $division_old = $project->division->name; ?>
                                                            @else
                                                                @if($implementing_office_old == $project->implementing_office_id)
                                                                    <th colspan="2">"</th>
                                                                @else
                                                                    <th colspan="2">{{$project->implementing_office->name}}</th>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        <?php $implementing_office_old = $project->implementing_office_id;?>
                                                        <td><!-- 2 -->
                                                            {{$project->name}}
                                                            <br>
                                                            {{ $project->project_code,4 }}
                                                        </td>
                                                        <td><!-- 3 -->
                                                            {{ $project->unit }}
                                                        </td>
                                                        <td><!-- 4 -->
                                                            {{$project->quantity}}
                                                        </td>
                                                        <td><!-- 5 -->
                                                            {{number_format($project_cost,2)}}
                                                        </td>
                                                        <td><!-- 6 -->
                                                            {{number_format($activity_full_weight,2)}}
                                                        </td>
                                                        <td><!-- 7 -->
                                                            {{number_format($expense_till_last_fy_quantity,2)}}
                                                        </td>
                                                        <td><!-- 8 -->
                                                            {{$expense_till_last_fy}}
                                                        </td>
                                                        <td><!-- 9 -->
                                                            {{number_format($expense_till_last_fy_weight,2)}}
                                                        </td>
                                                        <td><!-- 10 -->
                                                            {{number_format($yearly_quantity,2)}}
                                                        </td>
                                                        <td><!-- 11 -->
                                                            {{number_format($yearly_weight,2)}}
                                                        </td>
                                                        <td><!-- 12 -->
                                                            {{$allocation?$allocation->total_budget:0}}
                                                        </td>
                                                        <td><!-- 13 -->
                                                            {{number_format($first_trim_quantity,2)}}
                                                        </td>
                                                        <td><!-- 14 -->
                                                            {{number_format($first_trim_weight,2)}}
                                                        </td>
                                                        <td><!-- 15 -->
                                                            {{$allocation->first_trim}}
                                                        </td>
                                                        <td><!-- 16 -->
                                                            {{number_format($second_trim_quantity,2)}}
                                                        </td>
                                                        <td><!-- 17 -->
                                                            {{number_format($second_trim_weight,2)}}
                                                        </td>
                                                        <td><!-- 18 -->
                                                            {{$allocation?$allocation->second_trim:0}}
                                                        </td>
                                                        <td><!-- 19 -->
                                                            {{number_format($third_trim_quantity,2)}}
                                                        </td>
                                                        <td><!-- 20 -->
                                                            {{number_format($third_trim_weight,2)}}
                                                        </td>
                                                        <td><!-- 21 -->
                                                            {{$allocation?$allocation->third_trim:0}}
                                                        </td>
                                                        <td><!-- 22 -->
                                                            <small>
                                                                {{ $project->lastProgress?$project->lastProgress->project_remarks:'-' }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>जम्मा</th>
                                                <th></th>
                                                <th></th>
                                                <th>{{number_format($total_project_cost,2)}}</th>
                                                <th>{{$total_activity_full_weight}}</th>
                                                <th></th>
                                                <th>{{$total_expense_till_last_fy}}</th>
                                                <th>{{number_format($total_expense_till_last_fy_weight,2)}}</th>
                                                <th></th>
                                                <th>{{number_format($total_yearly_weight,2)}}</th>
                                                <th>{{$total_total_budget}}</th>
                                                <th></th>
                                                <th>{{number_format($total_first_trim_weight,2)}}</th>
                                                <th>{{$total_first_trim_budget}}</th>
                                                <th></th>
                                                <th>{{number_format($total_second_trim_weight,2)}}</th>
                                                <th>{{$total_second_trim_budget}}</th>
                                                <th></th>
                                                <th>{{number_format($total_third_trim_weight,2)}}</th>
                                                <th>{{$total_third_trim_budget}}</th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @endif
                        <div class="panel panel-default">
                            <div class="panel-footer">
                                <button id="btnExport" class="btn btn-success" data-loading-text="Exporting..." autocomplete="off">Export Report</button>
                                <button id="btnPrint" class="btn btn-success" data-loading-text="Printing..." autocomplete="off">Print Report</button>
                                <button class="btn btn-default" type="reset">Reset</button>
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
    <script type="text/javascript" src="{{asset('public/admin/js/jquery.table2excel.js')}}"></script>
    <script src="{{ asset('public/js/jquery.printElement.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
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