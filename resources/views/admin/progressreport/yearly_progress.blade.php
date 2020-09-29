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
                            <div id="dvData">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <td class="text-center">(नियम २१ को उपनियम (२) र नियम २५ को उपनियम (१) संग सम्बन्धित)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><br>नेपाल सरकार</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">सहरी विकास मन्त्रालय</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">सहरी विकास तथा भवन निर्माण बिभाग</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{ Auth::User()->implementingOffice->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <?php
                                            if($month){$trimonth = $month;}else{$trimonth = $trimester;}
                                            $fiscalyear = PMIS\Fiscalyear::where('id',intval(session()->get('pro_fiscal_year')))->first();
                                            $budgettopic = PMIS\BudgetTopic::where('id',$budget_topic_id)->first();
                                            $chalucapital = PMIS\LumpSumBudget::where('budget_topic_id',$budget_topic_id)->first();
                                            ?>
                                            आ.ब. {{$fiscalyear->fy}} को बार्षिक (तेस्रो चौमासिकसम्मको)  प्रगति विवरण
                                        </th>
                                    </tr>
                                    </thead>
                                </table>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th colspan="15">
                                            <span>बजेट उपशीर्षक न. :{{$budgettopic->budget_topic_num}} ({{$budgettopic->budget_head}})  {{$chalucapital->running_capital==4?"पूंजीगत खर्च":"चालू खर्च "}}</span>
                                        </th>
                                        </th>
                                        <th>
                                            <span class="pull-right">(रकम रु. हजारमा)</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        @if(isset($division_code))
                                            <th rowspan="2" colspan="2">डी का</th>
                                        @else
                                            <th rowspan="2" colspan="2">निकाय/कार्यालय</th>
                                        @endif
                                        <th rowspan="2">क्र.स. / आई.डी.</th>
                                        <th class="no_font" rowspan="2">क्रियाकलाप (Activity) विवरण </th>
                                        <th rowspan="2">इकाई</th>
                                        <th colspan="3">बार्षिक लक्ष्य </th>
                                        <th colspan="3">बार्षिक प्रगति </th>
                                        <th colspan="2">बार्षिक खर्च</th>
                                        <th colspan="2"> आयोजनाको कूल मध्ये हालसम्मको प्रगति </th>
                                        <th rowspan="2">कैफियत</th>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <th>परिमाण</th>
                                        <th>भार</th>
                                        <th>बजेट</th>
                                        <th>परिमाण</th>
                                        <th>भारित</th>
                                        <th>प्रतिशत</th>
                                        <th>रकम रु.</th>
                                        <th>प्रतिशत</th>
                                        <th>परिमाण</th>
                                        <th>खर्च रकम</th>
                                    </tr>
                                    <tr>
                                        <td>#</td>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $division_old = '';
                                    $implementing_office_old = '';
                                    $division_id_old = '';
                                    $class = '';
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
                                    $ioOld = 0;
                                    foreach($projects->sortBy('id') as $project){
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

                                    @foreach($projects as $index=>$project)

                                        <?php
                                        $i++;
                                        $project_cost = ($project->procurement->contract_amount > 0)?$project->procurement->contract_amount:$project->procurement->estimated_amount;
                                        $allocation = $project->allocation()->where('fy_id',intval(session()->get('pro_fiscal_year')))->orderBy('amendment','desc')->first();
                                        //yearly
                                        $total_budget=0;
                                        if($allocation){
                                            $total_budget = $allocation->total_budget;
                                        }

                                        $yearly_quantity=0;

                                        if($project_cost!=0){
                                            $yearly_quantity = ($total_budget/$project_cost)*100;

                                        }

                                        if($total_total_budget!=0){
                                            $total_yearly_weight += $yearly_weight = ($total_budget/$total_total_budget)*100;
                                        }else{
                                            $total_yearly_weight += $yearly_weight = 0;
                                        }
                                        // this trim/month progress

                                        $progresses = $project->progresses()->where('month_id',$month_id)->where('fy_id',intval(session()->get('pro_fiscal_year')))->first();
                                        $upto_progress = $project->progresses()->where('month_id','=',$month_id)->where('fy_id','<=',intval(session()->get('pro_fiscal_year')));
                                        $trimesters = get_trim($trimester->id);
                                        $total_trim_budget += $trim_budget = $allocation?$allocation->$trimesters:0;


                                        $trim_quantity =0;
                                        if($project_cost!=0){

                                            $trim_quantity = ($trim_budget/$project_cost)*100;
                                        }


                                        if($trim_quantity>0){
                                            $total_trim_weight += $trim_weight = ($trim_budget/$total_total_budget)*100;
                                        }else{
                                            $total_trim_weight += $trim_weight = 0;
                                        }
                                        $prog_trim_percentage = 0;
                                        $prog_trim_quantity = 0;
                                        $prog_trim_weight = 0;
                                        $prog_trim_expense = 0;
                                        $upto_prog_trim_quantity = 0;
                                        $upto_prog_trim_weight = 0;
                                        $upto_prog_trim_expense = 0;
                                        $upercentage=0;
                                        if($progresses){
                                            $prog_trim_percentage = $progresses->getOriginal()['current_physical_progress'];
                                            $prog_trim_quantity = $trim_quantity*($prog_trim_percentage/100);
                                            if($prog_trim_quantity>0){
                                                $total_prog_trim_weight += $prog_trim_weight = $trim_weight/$trim_quantity*$prog_trim_quantity;
                                            }
                                            else{
                                                $total_prog_trim_weight += $prog_trim_weight = 0;
                                            }
                                            $total_prog_trim_expense += $prog_trim_expense = $progresses->bill_exp + $progresses->cont_exp;
                                            foreach($upto_progress->get() as $uprogress){
                                                $trim = get_trim($uprogress->month->trimester->id);

                                                $utrim_budget = 0;
                                                if($allocation){
                                                    $utrim_budget = $allocation->$trim;
                                                }

                                                $utrim_quantity=0;

                                                if($project_cost!=0){

                                                    $utrim_quantity = ($utrim_budget/$project_cost)*100;

                                                }
                                                $utrim_weight = ($utrim_budget/$total_total_budget)*100;
                                                $upercentage = $uprogress->getOriginal()['current_physical_progress'];
                                                $upto_prog_trim_quantity += $uquantity = $utrim_quantity*($upercentage/100);
                                                if($utrim_quantity>0){
                                                    $upto_prog_trim_weight += $uweight = $utrim_weight/$utrim_quantity*$uquantity;
                                                    $total_upto_prog_trim_expense += $upto_prog_trim_expense += $uexpense = $uprogress->bill_exp + $uprogress->cont_exp;
                                                }else{
                                                    $upto_prog_trim_weight += $uweight = 0;
                                                    $total_upto_prog_trim_weight += $upto_prog_trim_weight;
                                                    $total_upto_prog_trim_expense += $upto_prog_trim_expense += $uexpense = $uprogress->bill_exp + $uprogress->cont_exp;
                                                }
                                            }
                                        }
                                        ?>
                                        <?php
                                        if($ioOld != $project->implementing_office_id){
                                            toggleClass($class);
                                        }
                                        $ioOld = $project->implementing_office->id;

                                        if($project->procurement->contract_amount > 0)
                                            $total_amount = $project->procurement->contract_amount;
                                        else
                                            $total_amount = $project->procurement->estimated_amount;
                                        ?>

                                        <tr class="{{$class}}">
                                            <th>{{ $index+1 }}</th>
                                            <th colspan="1">{{$project->implementing_office_setting->name}}</th><!-- 1 -->
                                            <td><!-- 2 -->
                                                {{explode(":", $project->code)[1]}}
                                            </td>
                                            <td><!-- 3 -->
                                                {{$project->name}}
                                            </td>
                                            <td class="no_font"><!-- 4 -->
                                                {{$project->unit}}
                                            </td>
                                            <td><!-- 5 -->
                                                {{number_format($yearly_quantity,0)}}
                                            </td>
                                            <td><!-- 6 -->
                                                {{number_format($yearly_weight,2)}}
                                            </td>
                                            <td><!-- 7 -->
                                                {{number_format($total_budget,2)}}
                                            </td>
                                            <td><!-- 8 -->
                                                {{number_format($upto_prog_trim_quantity,2)}}
                                            </td>
                                            <td><!-- 9 -->
                                                {{number_format($upto_prog_trim_weight,2)}}
                                            </td>
                                            <td><!-- 10 -->
                                                {{number_format($upercentage,2)}}
                                            </td>
                                            <td><!-- 11 -->
                                                {{number_format($prog_trim_expense,2)}}
                                            </td>
                                            <td><!-- 12 -->
                                                {{number_format($upto_prog_trim_quantity,2)}}
                                            </td>
                                            <td><!-- 13 -->
                                                {{number_format($upto_prog_trim_weight,2)}}
                                            </td>
                                            <td><!-- 14 -->
                                                {{number_format($upto_prog_trim_expense,3)}}
                                            </td>
                                            <td class="no_font"><!-- 15 -->
                                                @if($progresses && $progresses->count() != 0)
                                                    {{$progresses->project_remarks}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><!-- 6 -->
                                            {{number_format($total_trim_weight,2)}}
                                        </th>
                                        <th><!-- 7 -->
                                            {{number_format($total_trim_budget,2)}}
                                        </th>
                                        <th><!-- 8 -->
                                        </th>
                                        <th><!-- 9 -->
                                            {{number_format($total_yearly_weight,2)}}
                                        </th>
                                        <th><!-- 10 -->
                                            {{$total_total_budget}}
                                        </th>
                                        <th><!-- 11 -->
                                        </th>
                                        <th><!-- 12 -->
                                        </th>
                                        <th><!-- 13 -->
                                        </th>
                                        <th><!-- 14 -->
                                            {{$total_prog_trim_expense}}
                                        </th>
                                        <th><!-- 15 -->
                                            {{number_format($total_upto_prog_trim_weight,2)}}
                                        </th>
                                        <th><!-- 16 -->
                                            {{number_format($total_prog_trim_weight,2)}}
                                        </th>
                                        <th><!-- 17 -->
                                            {{$total_upto_prog_trim_expense}}
                                        </th>
                                        <th><!-- 18 -->
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
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