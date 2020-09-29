<link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h2>सम्पूर्ण आयोजनाको सारांश - समुहगत कार्यक्रम संख्या तथा अवस्था</h2>
                @if($implementingOffice!=0)
                    <h3>
                        {{ \PMIS\ImplementingOffice::find($implementingOffice)->name}}
                    </h3>
                @endif
                <h4>
                    @if(isset($till_now)) आ.व. 2061/062 देखि @endif

                    आ.व. {{ \PMIS\Fiscalyear::whereId(intval(($fy_id)))->first()->fy }}

                    @if(isset($till_now)) सम्मको @endif
                </h4>
            </div>
            <?php  $natureOfConstructionType=visibleNature_of_project();unset($natureOfConstructionType[0]);?>
            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <table class="table-condensed table table-striped">
                    <tr>
                        <td rowspan="2">Category</td>
                        <td rowspan="2">Sub Category</td>
                        <td rowspan="2" width="5px" class="text-center"> Number of Projects</td>
                        <td colspan="{{ sizeof($natureOfConstructionType) }}">Design Estimate</td>
                        <td colspan="{{ sizeof($natureOfConstructionType) }}">Tender Evaluation</td>
                        <td colspan="{{ sizeof($natureOfConstructionType) }}">Under Construction</td>
                        <td colspan="{{ sizeof($natureOfConstructionType) }}">Near To Completion</td>
                        <td colspan="{{ sizeof($natureOfConstructionType) }}">Total Completed</td>
                    </tr>
                    <tr>
                        @for($i=0;$i<5;$i++)
                            <?php
                                $j=1;
                            ?>
                            @foreach($natureOfConstructionType as $natureIndex=>$value)
                                <td>
                                    {{ getRomanValue()[$j ]}}
                                    <?php
                                        $j++;
                                    ?>
                                </td>
                            @endforeach
                        @endfor
                    </tr>
                    <?php
                    $implementingOffice=intval($implementingOffice);
                    $fy_id=intval($fy_id);
                        if(isset($till_now)){
                            $till_now=1;
                        }else{
                            $till_now=0;
                        }
                    $grandTotal=0;
                    foreach($natureOfConstructionType as $natureIndex=>$value){
                        $grandDesign[$natureIndex]=0;
                        $grandTender[$natureIndex]=0;
                        $grandUnderConst[$natureIndex]=0;
                        $grandNearToComp[$natureIndex]=0;
                        $grandTotalCompleted[$natureIndex]=0;
                    }
                    ?>
                    @foreach($project_groups as $groupIndex=>$project_group)
                        <tr>
                            <td rowspan="{{ $project_group->child->count() }}" width="10%">{{ $project_group->name }}</td>
                        @foreach($project_group->child as $index=>$child)
{{--                            {{ dd($child) }}--}}
                            @if($index!=0)
                                <tr>
                            @endif
                                    <td width="15%">{{ $child->name }}</td>
                                    <td>
                                        <?php $total=(listProgressStatusReport_v2($child, $fy_id,$implementingOffice,$till_now,$show_on_running,$budget_topic) )?>
                                        {{ $total->count() }}
                                        <?php $grandTotal+=$total->count() ?>
                                    </td>
                                    <?php
                                    foreach($natureOfConstructionType as $natureIndex=>$value){
                                        $design_estimate[$natureIndex]=0;
                                        $tender_evaluation[$natureIndex]=0;
                                        $underConstruction[$natureIndex]=0;
                                        $nearToCompletion[$natureIndex]=0;
                                        $completed[$natureIndex]=0;
                                    }
                                    foreach($total->get() as $index=>$projectsHere){
                                        if($projectsHere->progresses){
                                                $last_progress=$projectsHere->progresses()->orderBy('id','desc')->first();
                                                if($last_progress){
                                                    $progressTrack=$last_progress->progressTrack;
                                                    if($progressTrack->physical_percentage==5){
                                                        $design_estimate[$projectsHere->nature_of_project_id]++;
                                                    }elseif($progressTrack->physical_percentage==10){
                                                        $tender_evaluation[$projectsHere->nature_of_project_id]++;
                                                    }elseif($progressTrack->physical_percentage>10 && $progressTrack->physical_percentage<90){
                                                        $underConstruction[$projectsHere->nature_of_project_id]++;
                                                    }elseif($progressTrack->physical_percentage>=90 && $progressTrack->physical_percentage<100){
                                                        $nearToCompletion[$projectsHere->nature_of_project_id]++;
                                                    }elseif($progressTrack->physical_percentage==100){
                                                        $completed[$projectsHere->nature_of_project_id]++;
                                                    }
                                                }else{
                                                    $design_estimate[$projectsHere->nature_of_project_id]++;
                                                }
                                            }else{
                                            $design_estimate[$projectsHere->nature_of_project_id]++;
                                            }
                                        }
                                    foreach($natureOfConstructionType as $natureIndex=>$value){
                                        $grandDesign[$natureIndex]+=$design_estimate[$natureIndex];
                                        $grandTender[$natureIndex]+=$tender_evaluation[$natureIndex];
                                        $grandUnderConst[$natureIndex]+=$underConstruction[$natureIndex];
                                        $grandNearToComp[$natureIndex]+=$nearToCompletion[$natureIndex];
                                        $grandTotalCompleted[$natureIndex]+=$completed[$natureIndex];
                                    }
                                    ?>
                                    @foreach($natureOfConstructionType as $natureIndex=>$value)
                                        <td>
                                            {{ $design_estimate[$natureIndex]!=0?$design_estimate[$natureIndex]:'' }}

                                        </td>
                                    @endforeach
                                    @foreach($natureOfConstructionType as $natureIndex=>$value)
                                        <td>
                                            {{ $tender_evaluation[$natureIndex]!=0?$tender_evaluation[$natureIndex]:'' }}
                                        </td>
                                    @endforeach
                                    @foreach($natureOfConstructionType as $natureIndex=>$value)
                                        <td>
                                            {{ $underConstruction[$natureIndex]!=0?$underConstruction[$natureIndex]:'' }}
                                        </td>
                                    @endforeach
                                    @foreach($natureOfConstructionType as $natureIndex=>$value)
                                        <td>
                                            {{ $nearToCompletion[$natureIndex]!=0?$nearToCompletion[$natureIndex]:'' }}
                                        </td>
                                    @endforeach
                                    @foreach($natureOfConstructionType as $natureIndex=>$value)
                                        <td>
                                            {{ $completed[$natureIndex]!=0?$completed[$natureIndex]:"" }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                    @endforeach
                        <tr>
                            <td colspan="2" rowspan="2" class="text-center"><b>Total</b></td>
                            <td rowspan="2" class="text-center"><b>{{ $grandTotal }}</b></td>
                            @foreach($natureOfConstructionType as $natureIndex=>$value)
                                <td><b>{{ $grandDesign[$natureIndex] }}</b></td>
                            @endforeach
                            @foreach($natureOfConstructionType as $natureIndex=>$value)
                                <td><b>{{ $grandTender[$natureIndex] }}</b></td>
                            @endforeach
                            @foreach($natureOfConstructionType as $natureIndex=>$value)
                                <td><b>{{ $grandUnderConst[$natureIndex] }}</b></td>
                            @endforeach
                            @foreach($natureOfConstructionType as $natureIndex=>$value)
                                <td><b>{{ $grandNearToComp[$natureIndex] }}</b></td>
                            @endforeach
                            @foreach($natureOfConstructionType as $natureIndex=>$value)
                                <td><b>{{ $grandTotalCompleted[$natureIndex] }}</b></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td colspan="{{ sizeof($natureOfConstructionType) }}" align="center">
                            <b>{{ array_sum($grandDesign)}}</b>
                            </td>
                            <td colspan="{{ sizeof($natureOfConstructionType) }}" align="center">
                            <b>{{ array_sum($grandTender)}}</b>
                            </td>
                            <td colspan="{{ sizeof($natureOfConstructionType) }}" align="center">
                            <b>{{ array_sum($grandUnderConst)}}</b>
                            </td>
                            <td colspan="{{ sizeof($natureOfConstructionType) }}" align="center">
                            <b>{{ array_sum($grandNearToComp)}}</b>
                            </td>
                            <td colspan="{{ sizeof($natureOfConstructionType) }}" align="center">
                            <b>{{ array_sum($grandTotalCompleted)}}</b>
                            </td>
                        </tr>
                </table>
                    <?php
                        $i=1;
                    ?>
                    @foreach($natureOfConstructionType as $natureIndex=>$value)
                            {{ getRomanValue()[$i ]}} :: {{ $value }} &nbsp&nbsp&nbsp&nbsp&nbsp
                            <?php $i++; ?>
                    @endforeach

            </div>
        </div>
    </div>
</div>
<style>
    table, th, td,tr {
        border: 2px solid black;
    }
</style>
