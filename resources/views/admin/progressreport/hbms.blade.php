<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <head>
        <title>All report</title>
        <link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
        <style>

            body {
                line-height: 130%;
                font-size: 15px;
            }
            #heath_building{
                margin-top: 1px;
                font-size: 18px;
            }
            #project_name{
                font-size: 14px;
            }
            .header{
                font-size: 16px;
                font-weight: bolder;
            }

            .border-box{
                border: rgba(118, 118, 118, 0.39) 2px solid;
                margin: 0 28px;
                margin-bottom: 10px;

            }
            .sup {
                position: relative;
                bottom: 1ex;
                font-size: 80%;
            }

            fieldset {
                /*border: .5px solid #CCCCCC!important;*/
                font-size: 13px;
                line-height: 20px;
            }


            li {
                padding: 3px;
                font-size: 12px;
            }

            .legend {
                font-weight: bolder;
                font-size: 12px;
                margin-bottom: 0 !important;
            }

            table, th, td {
                border: 1px solid #515151;
                font-size: 12px;
                text-align: center;
                margin-bottom: 10px !important;
            }

            td{
                padding-left: 3px;
                padding-right: 3px;
            }

            table {
                width: 95%;
                margin: 0 auto;
            }

            th {
                text-align: center;
            }
            td{
                font-size: 12px;
            }
            .report{
                border: 1px solid black;
                min-height: 1000px;
                padding: 10px;
                margin-bottom: 10px;
            }
            .print {page-break-after:always;
            }
            i{
                font-size: smaller;
                margin: 10px;
            }
        </style>

    </head>
<body>
{{--<div>--}}
    {{--Total : {{ $count }}--}}
{{--</div>--}}
    {{--<button onclick="window.history.back();">Go Back</button>--}}
@forelse($projects as $index=>$project)
    <div class="container ">
        <div class="print">
            <div class="report">
                <div class="header">
                    <div class="text-center">
                        <p>???????????? ??????????????? ????????? ????????? ????????????????????? ???????????????</p>
                        <p>
                            <span id="heath_building">
                                {{ $project->monitoringOffice->name }}
                            </span>
                        </p>
                    </div>
                    <div class="pull-left" id="project_name">
                        @php
                            $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
                            if(!$setting){
                                $setting = $project;
                            }
                        @endphp
                        <span style="float: left;">
                        ???????????????????????? ?????????: {{ $setting->project_code }}
                        </span>
{{--                        <span style="float: right;">???????????? ??????????????????: {{ $project->budget_topic->budget_topic_num }} - {{ $project->budget_topic->budget_head }}</span>--}}
                        <span style="float: right;">???????????? ??????????????????: {{ $setting->budget_topic->budget_topic_num }} - {{ $setting->budget_topic->budget_head }}</span>
                        <span style="clear: both"></span>
                        <br>
                        ???????????????????????? ?????????: {{ $project->name }} ({{ $project->name_eng }})
                        @if($project->revisedFrom   )
                            <br>
                            ?????????????????? ???????????? ???????????????????????????: {{ $project->revisedFrom->name}} ({{ $project->revisedFrom->project_code }})
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
                <br>
                <!-- complete general and get in another file -->
                <div class="legend">???????????????????????? ????????????????????? ???????????????</div>
                <div class="general border-box">
                    <fieldset>
                        <div class="row">
                            <div class="col-xs-4">
                                <ul>
                                    <li>?????????????????? : {{ $project->implementing_office->name }} </li>
                                    <li>??????????????? :  {{$project->district->zone->name  }} </li>
                                    <li>?????????????????? :  {{$project->district->name  }} </li>
                                    <li>????????????????????? ????????????????????? :
                                    {{getLand()[$project->district->geo_id]  }} <!-- terai ho ki k ho nekaleko --></li>
                                    <li>???????????????????????? ??????????????? :
                                        @if($project->headquarter==0)
                                            ???????????? ???????????????
                                        @elseif($project->headquarter==1)
                                            ????????????
                                        @else
                                            ??????
                                        @endif
                                    </li>
                                    <li>???????????? ???????????? : {{ $project->fiscal_year->fy }} </li>
                                    <li>??????????????? ??????????????? : {{ $project->story_area_unite?getStoreyArea()[$project->story_area_unite ]:'N/A'}}</li>
                                    <li>??????????????? ??????????????????????????? :{{ $project->pr_code}}&nbsp;
                                        <small>sqft</small>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-4">
                                <ul>
                                    <li>??????????????????????????? ????????????????????? :
                                        {{ nature_of_project()['eng'][$project->monitoring_office_id][$project->nature_of_project_id]}}
                                    </li>
                                    <li>??????????????? :
                                        {{ $project->group?$project->group->parent->name:"N/A" }}
                                    </li><!--group id / group-->
                                    <li>??????-??????????????? :
                                        {{ $project->group?$project->group->name:"N/A" }}
                                    </li><!--group short/ subgroup-->
                                    <li>????????????????????? ???????????? :
                                        {{ $project->consturctionLocatedArea?$project->consturctionLocatedArea->located_area:'N/A' }}
                                    </li> <!--group full-->
                                    <li>??????????????????????????? ?????????????????? :
                                        {{ $project->show_on_running==1?"Running":"Completed" }}

                                            <ul>
                                                <li>
                                                    ????????????????????? ????????????????????? ???????????? : {{ $project->completed_date }}
                                                </li>
                                                <li>
                                                    ?????????????????????????????? ???????????? : {{ $project->ho_date }}
                                                </li>
                                                <li>
                                                    ?????????????????????????????? ???????????? ???.???. : @if($project->ho_fy){{ $project->ho_fiscalYear->fy }}@endif
                                                </li>

                                            </ul>

                                    </li>
                                    <li>????????????????????? ??????????????????????????? :
                                        {{ isset(swamittwo()[$project->swamittwo])?swamittwo()[$project->swamittwo]:'N/A' }}
                                        @if($project->swamittwo == 1)
                                            <ul>
                                                <li>??????????????? ????????????????????????????????? ??????????????? :
                                                    {{ isset(jaggaType()[$project->whose])?jaggaType()[$project->whose]:"N/A"  }}
                                                </li>
                                            </ul>
                                        @elseif($project->swamittwo == 2)
                                            <ul>
                                                <li>?????????????????? ?????? :
                                                    {{ $project->kittanumber }}
                                                </li>
                                                <li>????????? ?????? :
                                                    {{ $project->shitnumber }}
                                                </li>
                                            </ul>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-4">
                                <ul>

                                    <li>???????????? ????????????????????? ????????????/??????????????? :
                                        {{ isset(soilTest()[$project->soiltest])?soilTest()[$project->soiltest]:'N/A' }}
                                        @if($project->soiltest == 2)
                                            <ul>
                                                <li>????????? ????????????????????? ?????????????????? :
                                                    @if($project->baringcapacity != '-')
                                                        {{ $project->baringcapacity }} KN/m <span class="sup">2</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        @endif
                                    </li>
                                    <li>????????????????????????????????? ????????????????????? :
                                        {{ isset(bsType()[$project->bstype])?bsType()[$project->bstype]:'N/A' }}
                                    </li>
                                    <li>?????? ?????? ????????????????????? :
                                        {{ isset(rooftype()[$project->rooftype])?rooftype()[$project->rooftype]:'N/A' }}
                                    </li>
                                    <li>????????????????????????????????? ????????????????????? :
                                        {{ isset(doorWindow()[$project->doorwindow])?doorWindow()[$project->doorwindow]:'N/A' }}
                                    </li>
                                    <li>?????????????????? ????????????????????? :
                                        {{ isset(wallType()[$project->wall_type])?wallType()[$project->wall_type]:'N/A' }}
                                    </li>
                                    <li>?????????????????? ???????????? :
                                        {{ isset(designType()[$project->design_type])?designType()[$project->design_type]:'N/A' }}
                                    </li>
                                    <li>?????????????????? :
                                        {{ $project->description }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="legend">????????????????????? ???????????????????????? ???????????????</div>
                <div class="contract border-box">
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <ul style="margin-bottom: 0;">
                                    @if($project->procurement->contractor)
                                        <li>
                                            ????????????????????? ???????????????????????? :{{ $project->procurement->Contractor->name}} ({{ $project->procurement->Contractor->address }})
                                        </li>
                                    @elseif($project->procurement->JointVenture)
                                        <li>
                                            ????????????????????? ???????????????????????? : {{ $project->procurement->JointVenture->name}} ({{ $project->procurement->JointVenture->address }})
                                        </li>
                                    @else
                                        <li>
                                            ????????????????????? ???????????????????????? :
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-xs-4">
                                <ul>
                                    <li>?????????????????? ????????????????????? ???????????? :
                                        {{ $project->procurement->design_est_swikrit_miti=='0000-00-00'? "N/A" : $project->procurement->design_est_swikrit_miti}}
                                    </li>
                                    <li>?????????????????? ???.???. : {{ $project->procurement->estimated_amount*1000  }}</li>
                                    <li>????????????????????? ???.???. ????????? :
                                        {{ number_format($project->procurement->con_est_amt_net,2)}}
                                    </li>
                                    <li>???.???. ????????????????????? ???????????? :
                                        {{ $project->procurement->est_approved_date=='0000-00-00'? "N/A" : $project->procurement->est_approved_date}}
                                    </li>
                                    <li>
                                        ????????????????????? ?????????
                                        <ul>
                                            <li>
                                                ??????????????? ??????????????? : {{ number_format(round($project->procurement->contract_amount,2 ),2)}}
                                            </li>
                                            <li>
                                                <?php
                                                $contingency = 1 + $project->procurement->contingency / 100
                                                ?>
                                                ??????????????? ????????? ??????????????????????????????????????? ???????????? : {{ number_format(round(($project->procurement->contract_amount * $contingency) * 1.13/1000,2) ,3)}}
                                            </li>
                                        </ul>
                                    </li>
                                    <li>??????????????????????????? ???????????? :
                                        {{ $project->projectCost()}}
                                    </li>
                                    <li>????????????????????? ???????????? :
                                        {{ $project->procurement->method }}
                                    </li>
                                </ul>
                            </div>

                            <div class="col-xs-4">
                                <ul>
                                    <li>????????????????????? ??????????????? ???????????? (????????????????????????) :
                                        {{ $project->procurement->bid_does_ready_est=='0000-00-00'? "N/A" : $project->procurement->bid_does_ready_est}}
                                    </li>
                                    <li>????????????????????? ??????????????? ???????????? (??????????????????) :
                                        {{ $project->procurement->bid_does_ready_act=='0000-00-00'? "N/A" : $project->procurement->bid_does_ready_act}}
                                    </li>
                                    <li>No objection est1 :
                                        {{ $project->procurement->no_obj_est1=='0000-00-00'? "N/A" : $project->procurement->no_obj_est1}}
                                    </li>
                                    <li>No objection act1 :
                                        {{ $project->procurement->no_obj_act1=='0000-00-00'? "N/A" : $project->procurement->no_obj_act1}}
                                    </li>
                                    <li>????????????????????? ?????????????????? ???????????? (????????????????????????) :
                                        {{ $project->procurement->call_for_bid_est=='0000-00-00'? "N/A" : $project->procurement->call_for_bid_est}}
                                    </li>
                                    <li>????????????????????? ?????????????????? ???????????? (??????????????????) :
                                        {{ $project->procurement->call_for_bid_act=='0000-00-00'? "N/A" : $project->procurement->call_for_bid_act}}
                                    </li>
                                    <li>????????????????????? ?????????????????? ???????????? (????????????????????????) :
                                        {{ $project->procurement->bid_open_est=='0000-00-00'? "N/A" : $project->procurement->bid_open_est}}
                                    </li>
                                    <li>????????????????????? ?????????????????? ???????????? (??????????????????) :
                                        {{ $project->procurement->bid_open_act=='0000-00-00'? "N/A" : $project->procurement->bid_open_act}}
                                    </li>
                                    <li>????????????????????? ??????????????????????????? ????????????????????? ???????????? (????????????????????????) :
                                        {{ $project->procurement->bid_eval_est=='0000-00-00'? "N/A" : $project->procurement->bid_eval_est}}
                                    </li>
                                    <li>????????????????????? ??????????????????????????? ????????????????????? ???????????? (??????????????????) :
                                        {{ $project->procurement->bid_eval_act=='0000-00-00'? "N/A" : $project->procurement->bid_eval_act}}
                                    </li>

                                </ul>
                            </div>
                            <div class="col-xs-4">
                                <ul>
                                    <li>No objection est2 :
                                        {{ $project->procurement->no_obj_est2=='0000-00-00'? "N/A" : $project->procurement->no_obj_est2}}
                                    </li>
                                    <li>No objection-act2 :
                                        {{ $project->procurement->no_obj_act2=='0000-00-00'? "N/A" : $project->procurement->no_obj_act2}}
                                    </li>
                                    <li>????????????????????? ????????????????????? ???????????? :
                                        {{ $project->procurement->bolapatraswikriti=='0000-00-00'? "N/A" : $project->procurement->bolapatraswikriti}}
                                    </li>
                                    <li>????????????????????? ???????????? (????????????????????????) :
                                        {{ $project->procurement->con_sign_est=='0000-00-00'? "N/A" : $project->procurement->con_sign_est}}
                                    </li>
                                    <li>????????????????????? ???????????? (??????????????????) :
                                        {{ $project->procurement->contract_date=='0000-00-00'? "N/A" : $project->procurement->contract_date}}
                                    </li>
                                    <li>????????????????????? ???????????????????????? ???????????? (????????????????????????) :
                                        {{ $project->procurement->con_end_est=='0000-00-00'? "N/A" : $project->procurement->con_end_est}}
                                    </li>
                                    <li>????????????????????? ???????????????????????? ???????????? (??????????????????) :
                                        {{ $project->procurement->completion_date=='0000-00-00'? "N/A" : $project->procurement->completion_date}}
                                    </li>
                                    <li>??????????????????????????? ?????????????????? ???????????? :
                                        {{ $project->procurement->wo_date=='0000-00-00'? "N/A" : $project->procurement->wo_date}}
                                    </li>
                                    <li>?????????????????? ?????? :
                                        {{ $project->procurement->con_id_div=='0000-00-00'? "N/A" : $project->procurement->con_id_div}}
                                    </li>
                                    <li>
                                        ??????????????????:
                                        {{ $project->procurement->remarks=='' || NULL || '0' ? "N/A" : $project->procurement->remarks}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="timeExp">
                    <fieldset>
                        <div class="legend">??????????????? ?????? ???????????????????????? ???????????????</div>
                        <table>
                            <tr>
                                <th width="5%" rowspan="2">?????????.???</th>
                                <th width="20%" colspan="2"> ??????????????? ?????? ????????????</th>
                                <th width="10%" rowspan="2">??????????????? ?????? <br> ?????????????????? ????????????</th>
                                <th width="10%" rowspan="2">??????????????? ?????? <br>????????????????????? ????????????</th>
                                <th width="5%" rowspan="2">????????????????????? ?????????????????? ???/?????????</th>
                                <th width="50%" rowspan="2">??????????????????</th>
                            </tr>
                            <tr>
                                <th width="10%">????????????</th>
                                <th width="10%">????????????</th>
                            </tr>
                            @forelse($project->TimeExtension()->orderBy('end_date','asc')->get() as $index=>$extension)
                                <?php
                                $variation=false;
                                ?>
                                <tr>
                                    <td>{{ $index+1 }}</td>

                                    <td style="text-align: left">
                                        {{ $extension->start_date == '0000-00-00' || NULL || '' ? "N/A" : $extension->start_date}}
                                    </td>
                                    <td style="text-align: left">
                                        {{ $extension->end_date == '0000-00-00' || NULL || '' ? "N/A" : $extension->end_date}}
                                    </td>
                                    <td>
                                        {{ $extension->extended_on == '0000-00-00' || NULL || '' ? "N/A" : $extension->extended_on}}
                                    </td>
                                    <td>
                                        {{ verifiedFrom()[$extension->verified_from] }}
                                    </td>
                                    <td>
                                        @if($variation==false)
                                            @if($extension->liquidated_damage==1)
                                                ???
                                                <?php
                                                $variation=true;
                                                ?>
                                            @else
                                                ?????????
                                            @endif
                                        @else
                                            ???
                                        @endif
                                    </td>
                                    <td class="text-left">{{ $extension->remarks }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        ?????????????????? ?????????????????? ?????????
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </fieldset>
                </div>
                <div class="variation">
                    <fieldset>
                        <div class="legend">?????????????????????/ ??????????????? ?????????????????????/???????????? ???????????????????????? ???????????????</div>
                        <table>
                            <tr>
                                <th width="5%">?????????.???</th>
                                <th width="10%"> ?????????????????? ????????????</th>
                                <th width="10%"> ?????????</th>
                                <th width="10%"> ??????????????????</th>
                                <th width="10%">?????????????????????</th>
                                <th width="10%">????????????????????? ????????????</th>
                                <th>??????????????????</th>
                            </tr>
                            <?php
                            $variations_choose=variations_choose();
                            ?>
                            @foreach($project->variation->sortByDesc('id') as $index=>$variation)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td style="text-align: left">{{ $variation->vope_date }}</td>
                                    <td style="text-align: right">{{ $variation->amount }}</td>
                                    <td style="text-align: left">{{ $variations_choose[$variation->status]}}</td>
                                    <td>
                                        {{ number_format(($variation->amount/$project->procurement->contract_amount)*100,3) }} %
                                    </td>
                                    <td>
                                        {{ verifiedFrom()[$variation->verified_from] }}
                                    </td>
                                    <td style="text-align: left">{{ $variation->remarks }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </fieldset>
                </div>
                <div>
                    <fieldset>
                        <div class="legend">???????????? ????????? ??????????????? ?????????????????? ???????????????</div>
                        <table cellspacing="5px">
                            <tr>
                                <th width="5%">?????????.???</th>
                                <th width="10%">???.???.</th>
                                <th width="10%">????????? ???????????? (??????. ??????????????????)	</th>
                                <th width="10%">???????????????????????????????????????  ???????????? (??????. ??????????????????)	</th>
                                <th width="10%">??????????????? ?????????????????? %</th>
                                <th width="65%">??????????????? ??????????????????	</th>
                            </tr>
                            <?php
                                $totalExpenditure=0;
                                $totalExpenditureCont=0;
                                $old_Progress=$project->progresses()->where('fy_id','>=',$project->start_fy_id)->where('month_id',12)->where('fy_id','<',session()->get('pro_fiscal_year'))->get();
                                $index=1;
                            ?>

                            @foreach($old_Progress as $progress)
                                <tr>
                                    <td>
                                        {{ $index++ }}
                                    </td>
                                    <td>
                                        {{ $progress->fy->fy }}
                                    </td>
                                    <td style="text-align: right">
                                        {{ number_format($progress->bill_exp,3)}}
                                        <?php $totalExpenditure+= $progress->bill_exp ?>
                                    </td>
                                    <td style="text-align: right">
                                    {{ number_format($progress->cont_exp,3) }}
                                        <?php $totalExpenditureCont+= $progress->cont_exp ?>
                                    </td>
                                    <td>
                                        @if($progress->id == $project->progresses()->get()->last()->id)
                                            {{--{{ $progress->current_physical_progress }}%--}}
                                            @if($progress->progressTrack)
                                                {{ ($progress->progressTrack->physical_percentage ) }}%
                                            @endif
                                            {{--{{ ($progress->progressTrack->physical_percentage ) }}--}}
                                        @endif
                                    </td>
                                    <td width="30%" class="text-left">
                                        {{--<!-- Remove this condition later -->--}}
                                            {{ $progress->project_remarks }}
                                    </td>
                                </tr>
                            @endforeach
                            <?php
                                $progressThisYear = $project->progresses()->where('fy_id','>=',$project->start_fy_id)->where('fy_id',session()->get('pro_fiscal_year'))->orderBy('month_id','desc')->get();
                                $last = 0;
                            ?>
                            @foreach($progressThisYear as $index=>$progress)
                                @if($last == 1)
                                    <?php break; ?>
                                @endif
                                @if($progress->month_id == 12)
                                    <?php $last = 1;?>
                                @endif
                                <tr style="background: #aab7b0;">
                                    <td>
                                        {{ $index++ }}
                                    </td>
                                    <td>
                                        {{ $progress->fy->fy }} @if($last == 0 ) {{$progress->month->trimester->name}} @endif
                                    </td>
                                    <td style="text-align: right">
                                        {{ number_format($progress->bill_exp,3)}}
                                        <?php $totalExpenditure+= $progress->bill_exp ?>
                                    </td>
                                    <td style="text-align: right">
                                        {{ number_format($progress->cont_exp,3) }}
                                        <?php $totalExpenditureCont+= $progress->cont_exp ?>
                                    </td>
                                    <td>
                                        @if($progress->id == $project->progresses()->get()->last()->id)
                                            @if($progress->progressTrack)
                                                {{ ($progress->progressTrack->physical_percentage ) }}%
                                            @endif
                                            {{--{{ ($progress->progressTrack->physical_percentage ) }}--}}
                                        @endif
                                    </td>
                                    <td width="30%" class="text-left">
                                        {{--<!-- Remove this condition later -->--}}
                                        @if($progress->id == $project->progresses()->get()->last()->id)
                                            {{ $progress->project_remarks }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach


                            <tr>
                                <td>
                                </td>
                                <td>
                                    ???????????????
                                </td>
                                <td class="text-right">
                                    {{ number_format($totalExpenditure ,3)}}
                                </td>
                                <td class="text-right">
                                    {{ number_format($totalExpenditureCont ,3)}}
                                </td>
                                <td colspan="2" class="text-left">
                                    {{ number_format(($totalExpenditure + $totalExpenditureCont) ,3)}}
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                <div>
                    <fieldset>
                        <div class="legend">???????????? ????????????????????????</div>
                        <table cellspacing="5px">
                            <tr>
                                <th width="5%">?????????.???</th>
                                <th width="10%">???.???.</th>
                                <th>??????????????? ????????????</th>
                                <th>??????????????? ?????????????????????</th>
                                <th>?????????????????? ?????????????????????</th>
                                <th>?????????????????? ?????????????????????</th>
                                <th>??????????????? ???????????????</th>
                                <th>??????</th>
                                <th>??????????????????</th>
                            </tr>
                            <?php
//                            $totalExpenditure=0;
                            $recorded_fys = array_unique($project->allocation->pluck('fy_id')->toArray());
                            $index=1;
                            ?>
                            @foreach($recorded_fys as $fy)
                                <?php
                                $allocation = $project->allocation()->where('fy_id', $fy)->orderBy('amendment','desc')->first();

                                ?>
                                @if($allocation)
                                    <tr>
                                        <td>
                                            {{ $index++ }}
                                        </td>
                                        <td>
                                            {{ $allocation->fy->fy }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ $allocation->total_budget }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ $allocation->first_trim }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ $allocation->second_trim }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ $allocation->third_trim }}
                                        </td>
                                        <td style="text-align: right">
                                            {{--2073-074 ma gon loan and grant ko sum total budget bhanda badi cha .. --}}
                                            {{--data entry ma bigreko huna sakcha .. --}}
                                            {{--most of the projects ko allocation ma loan badi dekhiyeko cha ... --}}
                                            {{-- byPrabhat --}}
                                            @if($fy != 14)
                                                {{ $allocation->gon }}
                                            @endif
                                        </td>
                                        <td style="text-align: right">
                                            @if($fy != 14)
                                            {{ $allocation->loan }}
                                            @endif
                                        </td>
                                        <td style="text-align: right">
                                            @if($fy != 14)
                                            {{ $allocation->grants }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </fieldset>
                </div>

                <!-- Project Overview table -->

                <div>
                    <fieldset>
                        <div class="legend">Project Overview</div>
                        @php
                            $contract_date = $project->procurement->contract_date=='0000-00-00'? "N/A" : $project->procurement->contract_date;
                            $completion_date_first_contract = $project->procurement->completion_date=='0000-00-00'? "N/A" : $project->procurement->completion_date;
                            $completion_date_time_extension = $project->TimeExtension()->orderBy('end_date','desc')->first()->end_date ?? '';
                            //FY DETAILS
                            $current_fy = intval(session()->get('pro_fiscal_year'));

                            $fiscal_years= [];
                            $fiscal_years_title= [];
                            $total_allocation = [];
                            $financial_progress = [];
                            $physical_progress = [];
                            $end_fy = $current_fy;
                            try{
                                if($completion_date_first_contract !== ""){
                                    $fy_id = getFyId($completion_date_first_contract);
                                }
                                if($completion_date_time_extension !== "" && $completion_date_time_extension !== null){
                                    $fy_id = getFyId($completion_date_time_extension);
                                }

                                if($fy_id > $current_fy){
                                    $end_fy = $fy_id;
                                }
                            }catch (Exception $exception){
                            }

                            $fiscal_years = getFyFromTo($current_fy,$end_fy);
                            //Financial Progress AND PHYSICAL PROGRESS Current Fy:
                            $financial_progress[] = number_format(($totalExpenditure + $totalExpenditureCont) ,3) ?? 0;
                            $latest_progress =$project->progresses()->orderBy('id','desc')->first();
                            if($latest_progress !== null){
                                $remarks = ($latest_progress->progressTrack->progress !== null ? "(".$latest_progress->progressTrack->progress.")" : "(".$latest_progress->progressTrack->progress_eng.")") ?? '';
                                $physical_progress[] = $latest_progress->progressTrack->physical_percentage.' %'.$remarks ?? 'N/A' ;
                                $total_physical_progress_percentage = $remarks." / ".$latest_progress->progressTrack->physical_percentage.' %' ?? 'N/A';
                            }
                            $fy_count = 1;
                            foreach ($fiscal_years as $fy_id => $fiscal_year){
                                if($fy_count <=3){
                                    $fiscal_years_title[] = $fiscal_year;
                                    $total_allocation[] = $project->allocation()->where('fy_id', $fy_id)->orderBy('amendment','desc')->first()->total_budget ?? '-';
                                    if($fy_id !== $current_fy){
                                        $progress = $project->progresses()->where('fy_id',$fy_id)->where('month_id',12)->first();

                                        $financial_progress[] = $progress !== null ? ($progress->bill_exp + $progress->cont_exp) : '-';
                                        $remarks = $progress !== null ? "(".$progress->progressTrack->progress.")" : '';
                                        $physical_progress[] = $progress !== null ? $progress->progressTrack->physical_percentage.' %'.$remarks ?? 'N/A' : '-';
                                    }
                                    $fy_count++;
                                }
                            }
                            $total_allocation_this_fy = $project->allocation()->where('fy_id', session()->get('pro_fiscal_year'))->orderBy('amendment','desc')->first()->total_budget ?? 0;



                            //FY DETAILS
                            $contract_amount = number_format(round($project->procurement->contract_amount,2 ),2);
                            $total_liability_amount = $project->procurement->total_liability !== null && $project->procurement->total_liability !== "" ? $project->procurement->total_liability : '<font style="color:red;">Add Total Liability In Procurement</font>';
                            $total_cost = number_format(($totalExpenditure + $totalExpenditureCont) ,3) ?? 0;
                            $total_cost_percentage = $project->procurement->total_liability !== null && $project->procurement->total_liability !== "" && floatval($project->procurement->total_liability) > 0 ? ((($totalExpenditure + $totalExpenditureCont)/floatval($project->procurement->total_liability))*100)." %" : '-';
                            $total_liability_left_budget = $project->procurement->total_liability !== null && $project->procurement->total_liability !== "" && floatval($project->procurement->total_liability) > 0 ? number_format($project->procurement->total_liability -(($totalExpenditure + $totalExpenditureCont)+ $total_allocation_this_fy),3) ?? 0 : "N/A";
                            $total_liability_left_expenditure = $project->procurement->total_liability !== null && $project->procurement->total_liability !== "" && floatval($project->procurement->total_liability) > 0 ? number_format($project->procurement->total_liability -($totalExpenditure + $totalExpenditureCont),3) ?? 0 : "N/A";


                        @endphp

                        <table>
                            <tr>
                                <td colspan="2"></td>
                                <th colspan="2">????????????????????? ????????????: {{ $contract_date ?? '' }}</th>
                                <th colspan="2">?????????????????? ????????????????????? ?????????????????? ????????????????????? ???????????? ??????????????? ????????????: {{ $completion_date_first_contract ?? '' }}</th>
                                <th colspan="3">??????????????? ?????? ?????????????????? ??????????????? ?????????????????????????????? ????????????: {{ $completion_date_time_extension ?? '' }}</th>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <th colspan="3">????????????????????? ????????? (??????????????? ???????????????): {{ $contract_amount ?? '' }}</th>
                                <th colspan="3">???????????????????????? ????????? ?????????????????????: {!! $total_liability_amount ?? '' !!}</th>
                            </tr>
                            <tr>
                                <th colspan="9">??????????????????????????? ????????????????????? ???.???. ??????????????? ?????????????????? </th>
                            </tr>
                            <tr>
                                <th colspan="2">???.???.</th>
                                
                                <?php
                                    $start_fy_id = $project->start_fy_id;
                                    $end_fy_id = null;
//                                    if($contract_date) $start_fy_id= getFyId($contract_date);
                                    
                                    if($completion_date_time_extension){
                                        $end_fy_id=getFyId($completion_date_time_extension);
                                    }else{
                                        $end_fy_id=getFyId($completion_date_first_contract);
                                    }
                                    $fys = [];
                                    if ($start_fy_id && $end_fy_id){
                                        $fys = getFyFromTo($start_fy_id, $end_fy_id)->toArray();
                                    }


                                    //filter fys before contract dates with not expenditure
                                    $fys_before_contract = getFyFromTo($start_fy_id, getFyId($contract_date)-1)->toArray();
                                    foreach ($fys_before_contract as $fy){
                                        $progress = $project->progresses()->where('fy_id',getFyId($fy))->where('month_id',12)->first();
                                        $bill_exp = $progress ? $progress->bill_exp : null;
                                        $cont_exp = $progress ?$progress->cont_exp : null;
                                        $total_exp = $bill_exp + $cont_exp;
                                        if($total_exp == 0){
                                            unset($fys[array_search($fy, $fys)]);
                                        }
                                    }

                                ?>
                                @if($fys)
                                    @foreach($fys as $index => $fy)
                                        <th >{{ $fy }}</th>
                                    @endforeach
                                @endif
                            </tr>

                            <tr>
                                <th colspan="2">??????????????????????????? ????????????</th>
                                @if($fys)
                                    @foreach($fys as $fy)
                                        <?php $allocation = $project->allocation()->where('fy_id', getFyId($fy))->orderBy('amendment','desc')->first(); ?>
                                        <td >{{  $allocation ? $allocation->total_budget : '-' }}</td>
                                    @endforeach
                                @endif

                            </tr>
                            <tr>
                                <th colspan="2">????????????????????? ?????????????????? / ???????????? ?????????????????????</th>
                                @if($fys)
                                    @foreach($fys as $fy)
                                        <?php
                                            $progress = $project->progresses()->where('fy_id',getFyId($fy))->where('month_id',12)->first();
                                            $bill_exp = $progress ? $progress->bill_exp : null;
                                            $cont_exp = $progress ?$progress->cont_exp : null;
                                            $total_exp = $bill_exp + $cont_exp;
                                        ?>
                                        <td>{{ $total_exp ?? '-' }}</td>
                                    @endforeach
                                @endif
                                
                            </tr>
                            <tr>
                                <th colspan="2">??????????????? ?????????????????? </th>
                                @if($fys)
                                    @foreach($fys as $fy)
                                        <?php $progress = $project->progresses()->where('fy_id',getFyId($fy))->where('month_id',12)->first() ?>
                                        @if($progress)
                                            @if($progress->id == $project->progresses()->get()->last()->id)
                                                {{--{{ $progress->current_physical_progress }}%--}}
                                                @if($progress->progressTrack)
                                                    <td>{{ ($progress->progressTrack->physical_percentage ) }}%</td>
                                                @endif
                                                {{--{{ ($progress->progressTrack->physical_percentage ) }}--}}
                                            @else
                                                <td>-</td>
                                            @endif
                                        @endif
                                        
                                    @endforeach
                                @endif
                            </tr>

                            <tr>
                                <th colspan="3">????????????????????? ?????? ????????????????????? ??????????????????</th>
                                <th colspan="3">Total Cost: {{ $total_cost ?? '' }} / {{ $total_cost_percentage ?? '-' }}  </th>
                                <th colspan="3">??????????????? ??????????????????: {{ $total_physical_progress_percentage ?? '' }}</th>
                            </tr>
                            <tr>
                                <th colspan="4">???????????????????????? ???????????? ????????????????????? (???????????? ??????????????????) :{{ $total_liability_left_budget ?? ''}}</th>
                                <th colspan="5">???????????????????????? ???????????? ????????????????????? (???????????? ??????????????????) : {{ $total_liability_left_expenditure ?? ''}}</th>
                            </tr>
                        </table>
                    </fieldset>
                </div>

                <!-- Project Overview table ends-->
<!--
{{--                Added time periodic chart--}}
{{--                <div>--}}
{{--                    <fieldset>--}}
{{--                        <div class="legend">Time Periodic Chart</div>--}}
{{--                        <table cellspacing="5px">--}}
{{--                            <tr>--}}
{{--                                <th colspan="2" width="5%">?????????.???</th>--}}
{{--                                <th colspan="3" >???.???.</th>--}}
{{--                                <th colspan="1">????????????????????? ????????? </th>--}}
{{--                                <th colspan="1">????????????????????? ????????????</th>--}}
{{--                                <th colspan="1">????????????????????? ??????????????????????????? ????????????</th>--}}
{{--                                <th colspan="2">????????????</th>--}}
{{--                                <th colspan="1">??????????????? ??????????????????</th>--}}
{{--                                <th colspan="1">????????? ?????????????????????</th>--}}
{{--                                <th colspan="1">????????????????????? ????????????</th>--}}
{{--                                <th colspan="1">??????????????????</th>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th colspan="2"></th>--}}
{{--                                @php--}}
{{--                                    $fiscal_years = getFyFromTo(17,19);--}}
{{--                                @endphp--}}
{{--                                @foreach($fiscal_years as $fiscal_year)--}}
{{--                                    <th colspan="1">{{ $fiscal_year }}</th>--}}
{{--                                @endforeach--}}
{{--                                <th colspan="1"></th>--}}
{{--                                <th colspan="1"></th>--}}
{{--                                <th colspan="1"></th>--}}
{{--                                <th colspan="1">?????? ???.??? ?????? ????????????</th>--}}
{{--                                <th colspan="1">????????? ?????????????????? ????????????</th>--}}
{{--                                <th colspan="1"></th>--}}
{{--                                <th colspan="1"></th>--}}
{{--                                <th colspan="1"></th>--}}
{{--                                <th colspan="1"></th>--}}

{{--                            </tr>--}}
{{--                            @php--}}
{{--                                $index = 1;--}}
{{--                            @endphp--}}
{{--                            <tr>--}}
{{--                                <td colspan="2">{{ $index }}</td>--}}
{{--                                @foreach($fiscal_years as $id =>$fiscal_year)--}}
{{--                                    <?php--}}
{{--                                        $allocation =$project->allocation()->where('status', 1)->where('fy_id', $id)->first();--}}
{{--                                        try{--}}
{{--                                    ?>--}}
{{--                                        <td>{{ number_format($allocation->total_budget* 1000,3)  }}</td>--}}
{{--                                    <?php--}}
{{--                                        }catch (Exception $exception){--}}

{{--                                        }--}}
{{--                                    ?>--}}

{{--                                @endforeach--}}
{{--                                <td>{{ number_format(round(($project->procurement->contract_amount * $contingency) * 1.13,2) ,3)}}</td>--}}
{{--                                <td>{{ $project->procurement->contract_date=='0000-00-00'? "N/A" : $project->procurement->contract_date}}</td>--}}

{{--                                <td>{{ $project->procurement->completion_date=='0000-00-00'? "N/A" : $project->procurement->completion_date}}</td>--}}

{{--                                <?php--}}
{{--                                    $progresses = $project->progresses()->get()->where('fy_id',session()->get('pro_fiscal_year'));--}}
{{--                                    $total_exp_this_fy = 0;--}}

{{--                                    foreach ($progresses as $progress){--}}

{{--                                        $total_exp_this_fy += $progress->bill_exp;--}}
{{--                                        $total_exp_this_fy += $progress->cont_exp;--}}
{{--                                    }--}}

{{--                                ?>--}}

{{--                                <td colspan="1">{{ number_format($total_exp_this_fy * 1000 ,3) }}</td>--}}

{{--                                <td colspan="1">{{ number_format(($totalExpenditure + $totalExpenditureCont)*1000 ,3)}}</td>--}}
{{--                                <td colspan="1">--}}
{{--                                    <?php--}}
{{--                                      try{--}}
{{--                                    ?>--}}
{{--                                    @if($progress->id == $project->progresses()->get()->last()->id)--}}
{{--                                        @if($progress->progressTrack)--}}
{{--                                            {{ ($progress->progressTrack->physical_percentage ) }}%--}}
{{--                                        @endif--}}
{{--                                        --}}{{--{{ ($progress->progressTrack->physical_percentage ) }}--}}
{{--                                    @endif--}}
{{--                                    <?php--}}
{{--                                        }catch (Exception $exception){--}}

{{--                                        }--}}
{{--                                    ?>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    {{ number_format($project->projectCost() *1000  ,3)}}--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    {{ number_format(($project->projectCost() - ($totalExpenditure + $totalExpenditureCont)) *1000,3) }}--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <?php--}}
{{--                                    try{--}}
{{--                                    ?>--}}
{{--                                    --}}{{--<!-- Remove this condition later -->--}}
{{--                                    @if($progress->id == $project->progresses()->get()->last()->id)--}}
{{--                                        {{ $progress->project_remarks }}--}}
{{--                                    @endif--}}
{{--                                        <?php--}}
{{--                                        }catch (Exception $exception){--}}

{{--                                        }--}}
{{--                                        ?>--}}

{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        </table>--}}

{{--                    </fieldset>--}}
{{--                </div>--}} -->
            </div>
            <i>Data Extracted from system on <?= date('Y-m-d') ?></i>
        </div>
    </div>
@empty
    <script type="text/javascript">
        {{ Session::flash('fail_info','" No Data Available"')  }}
        window.location = "{{ URL::previous()}}";
    </script>

@endforelse
@if(isset($paginate))
    {!! $projects->appends(Request::input())->render() !!}
@endif
<footer class="text-center">HBMS Report <br></footer>
</body>
</html>
