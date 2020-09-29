@extends('layouts.admin_layout')

@section('headerContent')
    <style type="text/css">
        .table{
            background-color:#ffffff;
            border:none;
        }
        .table td{
            border-top: none !important;
        }
    </style>
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
                                    <td colspan="2" align="center">
                                        <h3>कार्यक्रम संशोधन फारम</h3>
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
                                                <td> कार्यक्रम/आयोजनाको नाम :{{\PMIS\BudgetTopic::whereId($budget_topic_id)->first()->budget_head }}</td>
                                            </tr>
                                            <tr>
                                                <td>४. </td>
                                                <td> आयोजना शुरु भएको मिति: </td>
                                            </tr>
                                            <tr>
                                                <td>५. </td>
                                                <td> आयोजना पुरा हुने मिति: </td>
                                            </tr>
                                            <tr>
                                                <td>६. </td>
                                                <td> स्थान/जिल्ला </td>
                                            </tr>
                                            <tr>
                                                <td>७. </td>
                                                <td> आयोजनाको कूल लागत</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table">
                                            <tr>
                                                <td>८. </td>

                                                <td> बार्षिक बजेट :</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="table">
                                                        <tr>
                                                            <td>क) आन्तरिक</td>
                                                            <td>
                                                                १) नेपाल सरकार : <br>
                                                                २) स्थानीय निकाय/संस्था <br>
                                                                ३) जनसहभागिता <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>ख) बैदेशिक</td>
                                                            <td>
                                                                १) ऋण : <br>
                                                                २) अनुदान : <br>
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
                                                    @if(isset($division_code))
                                                        <th rowspan="2" colspan="2">डी का</th>
                                                    @else
                                                        <th rowspan="2" colspan="2">निकाय/कार्यालय</th>
                                                    @endif
                                                    <th rowspan="2">क्र.स. / आई.डी.</th>
                                                    <th rowspan="2">क्रियाकलाप (Activity) विवरण </th>
                                                    <th rowspan="2">इकाई</th>
                                                    <th colspan="3">स्वीकृत कार्यक्रमको बार्षिक लक्ष्य </th>
                                                    <th colspan="3">हालसम्मको  प्रगति </th>
                                                    <th colspan="3">प्रस्तावित संशोधन</th>
                                                    <th colspan="2"> फरक अंक घटी बढी </th>
                                                    <th rowspan="2">संशोधन गर्नुपर्नाको कारण</th>
                                                </tr>
                                                <tr>
                                                    <th>परिमाण</th>
                                                    <th>भार</th>
                                                    <th>बजेट</th>
                                                    <th>परिमाण</th>
                                                    <th>भारित</th>
                                                    <th>खर्च</th>
                                                    <th>परिमाण</th>
                                                    <th>भार</th>
                                                    <th>बजेट</th>
                                                    <th>परिमाण</th>
                                                    <th>बजेट</th>
                                                </tr>
                                                <tr>
                                                    <td>0</td>
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $division_old = '';
                                            $implementing_office_old = '';
                                            $division_id_old = '';
                                            $class = '';
                                            $total_amount = 0;
                                            $earlier_total_yearly_budget = 0;
                                            $total_total_budget = 0;
                                            foreach($projects as $project){
                                                $amend = $project->allocation->sortByDesc('amendment');
                                                $amendment = $amend->get(count($amend)-1);
                                                if(count($amend)>1) $earlier_amendment = $amend->get(count($amend)-2); else $earlier_amendment = '';
                                                if($earlier_amendment){
                                                    $earlier_total_yearly_budget += $earlier_amendment->total_budget;
                                                }
                                                $total_total_budget += $amendment->total_budget;
                                            }
                                            ?>
                                            @foreach($projects as $index=>$project)
                                            <?php
                                                $amend = $project->allocation->sortByDesc('amendment');
                                                $amendment = $amend->get(count($amend)-1);
                                                if(count($amend)>1) $earlier_amendment = $amend->get(count($amend)-2); else $earlier_amendment = '';
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
                                                $implementing_office_old = $project->implementing_office_id;
                                                if($project->procurement->contract_amount > 0)
                                                    $total_amount = $project->procurement->contract_amount;
                                                else
                                                    $total_amount = $project->procurement->estimated_amount;
                                            ?>
                                                @if($earlier_amendment)
                                                    @if(($amendment->total_budget - $earlier_amendment->total_budget) != 0)
                                                        <tr class="{{$class}}">
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
                                                                    <th colspan="2">{{$project->implementing_office->name}}</th>
                                                                @endif
                                                            @endif
                                                            <td><!-- 2 -->
                                                                {{$project->project_code,4}}
                                                            </td>
                                                            <td width="20%"><!-- 3 -->
                                                                {{$project->name}}
                                                            </td>
                                                            <td><!-- 4 -->
                                                                {{ $project->unit }}
                                                            </td>
                                                            <td><!-- 5 -->
                                                                @if($earlier_amendment)
                                                                    {{number_format(($earlier_amendment->total_budget/$total_amount)*100,2)}}
                                                                @else
                                                                    0
                                                                @endif

                                                            </td>
                                                            <td><!-- 6 -->
                                                                @if($earlier_amendment)
                                                                    {{number_format(($earlier_amendment->total_budget/$earlier_total_yearly_budget)*100,2)}}
                                                                @else
                                                                    0
                                                                @endif
                                                            </td>
                                                            <td><!-- 7 -->
                                                                @if($earlier_amendment)
                                                                    {{$earlier_amendment->total_budget}}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td><!-- 8 -->

                                                            </td>
                                                            <td><!-- 9 -->

                                                            </td>
                                                            <td><!-- 10 -->

                                                            </td>
                                                            <td><!-- 11 -->
                                                                {{number_format(($amendment->total_budget/$total_amount)*100,2)}}
                                                            </td>
                                                            <td><!-- 12 -->
                                                                {{number_format(($amendment->total_budget/$total_total_budget)*100,2)}}
                                                            </td>
                                                            <td><!-- 13 -->
                                                                {{$amendment->total_budget}}
                                                            </td>
                                                            <td><!-- 14 -->
                                                                @if($earlier_amendment)
                                                                    {{$for_rem = number_format((($amendment->total_budget/$total_amount)*100) - (($earlier_amendment->total_budget/$total_amount)*100),2)}}
                                                                @else
                                                                    {{$for_rem = number_format(($amendment->total_budget/$total_amount)*100,2) - 0}}
                                                                @endif
                                                            </td>
                                                            @if($earlier_amendment)
                                                                @if(($amendment->total_budget - $earlier_amendment->total_budget) != 0)
                                                                    <td class="danger">
                                                                        {{$amendment->total_budget - $earlier_amendment->total_budget}}
                                                                    </td>
                                                                @else
                                                                        <td>-</td>
                                                                @endif
                                                            @else
                                                                <td class="danger">
                                                                    {{$amendment->total_budget}}
                                                                </td>
                                                            @endif
                                                            <td><!-- 16 -->
                                                                @if($for_rem < 0)
                                                                    कार्य प्रगति कम, बजेट बचत
                                                                @elseif($for_rem > 0)
                                                                    @if($earlier_amendment)
                                                                        कार्य प्रगति अनुसार बजेट नपुग
                                                                    @else
                                                                        नयाँ कार्यक्रम थप गरिएको
                                                                    @endif
                                                                @else
                                                                    @if($earlier_amendment)
                                                                        -
                                                                    @else
                                                                        नयाँ कार्यक्रम थप गरिएको
                                                                    @endif
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th><!-- 1 -->
                                                    </th>
                                                    <th><!-- 2 -->
                                                    </th>
                                                    <th><!-- 3 -->
                                                    </th>
                                                    <th><!-- 4 -->
                                                    </th>
                                                    <th><!-- 5 -->
                                                    </th>
                                                    <th><!-- 6 -->

                                                    </th>
                                                    <th><!-- 7 -->

                                                    </th>
                                                    <th><!-- 8 -->
                                                    </th>
                                                    <th><!-- 9 -->

                                                    </th>
                                                    <th><!-- 10 -->

                                                    </th>
                                                    <th><!-- 11 -->
                                                    </th>
                                                    <th><!-- 12 -->

                                                    </th>
                                                    <th><!-- 13 -->
                                                    </th>
                                                    <th><!-- 14 -->
                                                    </th>
                                                    <th><!-- 15 -->

                                                    </th>
                                                    <th><!-- 16 -->
                                                    </th>
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