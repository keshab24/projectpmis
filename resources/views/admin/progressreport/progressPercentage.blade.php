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
                <div class="text-center">
                    <h2>डिभिजन गत सारंश</h2>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div id="dvData">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>
                                    क्र. स
                                </th>
                                <th>
                                    कार्यान्यन निकाय
                                </th>
                                <th>
                                    भौतिक प्रगति
                                </th>
                            </tr>
                            @foreach($implementingOffices as $index=>$office)
                                <tr>
                                    <td>
                                        {{ $index+1 }}
                                    </td>
                                    <td>
                                        {{ $office->name }}
                                    </td>
                                    <td>
                                        <?php
                                        $monitoringOffice=array();
                                        if(Auth::USer()->implementingOffice->isMonitoring==1){
                                            $monitoringOffice=Auth::User()->implementingOffice()->pluck('id')->toArray();
                                        }else{
                                            $monitoringOffice=Auth::User()->implementingOffice->implementingSeesMonitor->pluck('id')->toArray();
                                        }
                                        $sumPercentage=0; $count=0; ?>
                                        @foreach($office->projects()->whereIn('monitoring_office_id',$monitoringOffice)->where('show_on_running','1')->get() as $project)
                                            @if($project->lastProgress)
                                                @if($project->lastProgress->progressTrack)
                                                    <?php $sumPercentage=$sumPercentage+$project->lastProgress->progressTrack->physical_percentage ?>
                                                    <?php $count++; ?>
                                                @endif
                                            @endif
                                        @endforeach
                                        {{ $count!=0?number_format($sumPercentage/$count ,3):0}}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel panel-default">
                        <div class="panel-footer">
                            <button id="btnExport" class="btn btn-success" data-loading-text="Exporting..." autocomplete="off">Export Report</button>
                            <button id="btnPrint" class="btn btn-success" data-loading-text="Printing..." autocomplete="off">Print Report</button>
                        </div>
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