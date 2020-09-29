@extends('layouts.admin_layout')

@section('headerContent')
    <style>
        .detail {
            width: 1200px;
            background: white;
            margin: 10px auto;
            padding: 20px 10px;
            box-shadow: 1px 1px 1px 1px #ddd;
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
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-6 col-lg-6">
                        <div class="table-responsive pro_hide_y centered">
                            {!!Form::open(['route'=>['report.daily.progress', $project->id],'method'=>'get','class'=>'showSavingOnSubmit'])!!}
                            <table class="table table-striped table-hover col-offset-6 centered">
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                </tr>
                                <tr>
                                    <td>{!! Form::text('from',$_GET['from']??null,['class'=>'form-control nepaliDate', 'id'=>'date_from']) !!}</td>
                                    <td>{!! Form::text('to',$_GET['to']??null,['class'=>'form-control nepaliDate', 'id'=>'date_to']) !!}</td>
                                </tr>
                            </table>
                            <div class="panel panel-default">
                                <div class="panel-footer">
                                    <button class="btn btn-success" data-loading-text="Processing..."
                                            autocomplete="off">Submit to Proceed
                                    </button>
                                    <button class="btn btn-default" type="reset">Reset</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    
                    
                    {{-- Backup Section--}}

                @if(isset($progresses) && !$progresses->isEmpty())
                        @foreach($progresses as $progress_index => $progress)
                            
                            <div class="detail" id="detail{{$progress_index}}">
                                @if($project->id > 11)
                                    <span class="pull-right " style="font-size: 30px; margin-right: 20px;" ><a class="fa fa-pencil" href="{{ route('daily.progress.edit', $progress->id) }}"></a></span>
                                @endif
                                <table class="table table-bordered main-table">
                                    <thead>
                                        <tr>
                                            <th colspan="9" style="text-align:center;font-size: 22px">DAILY PROGRESS REPORT
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="6">
                                                <p>Project Title: {{ $project->name }}<br>
                                                    Client: {{ $project->implementing_office->getOriginal()['name'] }}<br>
                                            </th>
                                            <th colspan="3" class="progress_date">Date: {{ $progress->date }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="6">
                                                    Location: {{ $project->address }}</p>
                                            </th>
                                            <th style="text-align:right" colspan="3">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            {!! dd('here') !!}
                                            {{--Manpower at Site--}}
                                            <td colspan="4">
                                                <table class="table table-bordered" style="background:none">
                                                    <tr>
                                                        <th width="20%">A</th>
                                                        <th width="40%" colspan="3">Manpower at site</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="20%">S.N</th>
                                                        <th width="40%">Description</th>
                                                        <th width="20%">Unit/Name</th>
                                                        <th width="20%">Quantity/Attendance</th>
                                                    </tr>
                                                    <?php $equipment_index = $manpower_index = $material_index = 1;?>
                                                    @if($progress->manpower !== null)
                                                        @foreach($progress->manpower as $manpower_type_title => $manpower_type)
                                                        <tr>
                                                            <td colspan="4" style="font-size:large">
                                                                <strong>{{ $manpower_type_title}}</strong>
                                                            </td>
                                                        </tr>
                                                        @foreach($manpower_type as $manpower_title => $manpower)
                                                            <tr>
                                                                <td width="20%">{{ $manpower_index }}</td>
                                                                <td width="40%">{{ $manpower_title }}</td>
        
                                                                
                                                                @if(is_array($manpower))
                                                                <td colspan="2">
                                                                    @if(array_key_exists('attendence', $manpower))
                                                                            @foreach($manpower['attendence'] as $id=>$attendance)
                                                                                <?php $member = PMIS\Engineer::find($id);?>
                                                                                    - {{ $member->name }}  @if($id)<span class="pull-right" style="color: green">Present</span> @else<span class="pull-right" style="color: red">Absent</span> @endif
                                                                                <div class="clearfix"></div>
                                                                            @endforeach
                                                                    @endif
                                                                </td>
                                                                @else
                                                                    {{-- amount --}}
                                                                    <td colspan="2" ><span class="pull-right">{{$manpower}}</span></td>
                                                                @endif
                                                            </tr>
                                                            <?php $manpower_index++; ?>
                                                        @endforeach
                                                    @endforeach
                                                    @endif
                                                    
                                                    {{--Materials stock day ends--}}
                                                    <tr>
                                                        <th width="20%">C</th>
                                                        <th width="40%" colspan="3">Materials stock day ends</th>
                                                    </tr>
                                                    @if($progress->materials !== null)
                                                        @foreach($progress->materials as $material_title => $material)
                                                            @if ($material['quantity'])
                                                                <tr>
                                                                    <td width="20%">{{ $material_index }}</td>
                                                                    <td width="40%">{{ $material_title }}</td>
                                                                    <td width="20%">{{ $material['unit']??'' }}</td>
                                                                    <td width="20%">{{ $material['quantity']??'' }}</td>
                                                                </tr>
                                                                <?php $material_index++; ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                            
                                            {{--Tools and Equipments at Site--}}
                                            <td colspan="5">
                                                <table class="table table-bordered" style="background:none">
                                                    <tr>
                                                        <th width="10%">S.N</th>
                                                        <th width="40%">Description</th>
                                                        <th width="10%">Unit</th>
                                                        <th width="20%">Quantity</th>
                                                        <th width="20%">Remarks</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="10%">B</th>
                                                        <th width="40%">Tools and Equipment at site</th>
                                                        <th width="10%"></th>
                                                        <th width="20%"></th>
                                                        <th width="20%"></th>
                                                    </tr>
                                                    @if($progress->equipments !== null)
                                                        @foreach($progress->equipments as $equipment_title => $equipment)
                                                            @if ($equipment['quantity'])
                                                                <tr>
                                                                    <td width="10%">{{ $equipment_index }}</td>
                                                                    <td width="40%">{{ $equipment_title }}</td>
                                                                    <td width="10%">{{ $equipment['unit']??'' }}</td>
                                                                    <td width="20%">{{ $equipment['quantity']??'' }}</td>
                                                                    <td width="20%"></td>
                                                                </tr>
                                                                <?php $equipment_index++; ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>D</th>
                                            <th colspan="3">Weather Condition (Sunny/Cloudy/Rainning):</th>
                                            <th>E</th>
                                            <th>Temperature ( &deg; C)</th>
                                            <th>Max</th>
                                            <th>Min</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td colspan="2">{{ $progress->weather }} </td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $progress->temperature['max'] }}</td>
                                            <td>{{ $progress->temperature['min'] }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>F</th>
                                            <th colspan="7">Problems Encountered / Site Issues:</th>
                                            <th></th>
                                        </tr>
                                        @if($progress->problems !== null)
                                            @foreach($progress->problems as $problem_index => $problem)
                                                <tr>
                                                    <th>{{ integerToRoman($problem_index + 1) }}</th>
                                                    <th colspan="7">{{ $problem }}</th>
                                                    <th></th>
                                                </tr>
                                            @endforeach
                                        @endif
                                        
                                        {{--Quantity Work Done--}}
                                        <tr>
                                            <th>G</th>
                                            <th>Code</th>
                                            <th colspan="3">Quantity Work Done: <span class="pull-right">Block</span></th>
                                            <th>Unit</th>
                                            <th>Q</th>
                                            <th>Remarks</th>
                                            <th>% Progress</th>
                                        </tr>
                                        <?php
                                        $activity_index = 1;
                                        ?>
                                        @if($progress->activities && count($progress->activities))
                                            @foreach($progress->activities as $activity_title => $activity)
                                                <?php
                                                $id= intval($activity['id']);
                                                $work_activity = \PMIS\WorkActivity::find($id);
                                                ?>
                                                @if($work_activity)
                                                    <tr>
                                                        <td>{{ integerToRoman($activity_index) }}</td>
                                                        <td>{{ $work_activity->code }}</td>
                                                        <td colspan="3">{{ $work_activity->title }} <span
                                                                    class="pull-right">{{ optional(\PMIS\ProjectBlocks::find($activity['block']))->block_name ??'N/A' }}</span>
                                                        </td>
                                                        <td>{{ $work_activity->unit }}</td>
                                                        <td>{{ $activity['q']??'N/A' }}</td>
                                                        <td>{{ $activity['remarks']??'N/A' }}</td>
                                                        <td>{{ $activity['progress']??'N/A' }}</td>
                                                    </tr>
                                                @endif
                                                <?php $activity_index++?>
                                            @endforeach
                                        @endif
    
                                        <tr>
                                            <th>H</th>
                                            <th colspan="8">Quality Control</th>
                                        </tr>
                                        <tr>
                                            <th>SN</th>
                                            <th>Block</th>
                                            <th>Material Detail</th>
                                            <th>Sample Code</th>
                                            <th>Remarks</th>
                                            <th>Date of Sample Taken</th>
                                            <th>For Client</th>
                                            <th>For Contractor</th>
                                            <th>For Consultant</th>
                                        </tr>
                                        <?php
                                        $sample_index = 1;
                                        ?>
                                        @if($progress->samples && count($progress->samples))
                                            
                                            @foreach($progress->samples as $sample_title => $sample)
                                                <?php
                                                $master_sample = \PMIS\Material::find($sample['id']);
                                                ?>
                                                @if($sample)
                                                    <tr>
                                                        <td>{{ integerToRoman($sample_index) }}</td>
                                                        <td>{{ optional(\PMIS\ProjectBlocks::find($sample['block']))->block_name ?? "" }}</td>
                                                        <td>{{ $master_sample->title }}</td>
                                                        <td>{{ $sample['sample_code']??"" }}</td>
                                                        <td>{{ $sample['remarks']??"" }}</td>
                                                        <td>{{ $sample['sample_taken_date']??"" }}</td>
                                                        
                                                            
                                                                @foreach(['for_client', 'for_contractor', 'for_consultant'] as $index)
                                                                    @if(array_key_exists($index, $sample))
                                                                        <td>
                                                                                @foreach($sample[$index] as $id)
                                                                                    <li style="list-style-type: none;">{{ optional(\PMIS\Engineer::whereId($id)->first())->name }}</li>
                                                                                @endforeach
                                                                        </td>
                                                                    @else
                                                                        <td>
                                                                    @endif
                                                                @endforeach
                                                @endif
                                                <?php $sample_index++?>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="row noExl">
                                    @if($project->activityLogs()->where('daily_progress_id', $progress->id)->where('type',2)->get()->count())
                                        @foreach($project->activityLogs()->where('daily_progress_id', $progress->id)->where('type',2)->get() as $progress_activity)
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        @foreach($progress_activity->ActivityLogFiles as $i => $photo)
                                                            <div class="col-sm-2">
                                                                <?php
                                                                    $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
                                                                    $videoExtensions = ['mp4', 'ogg', 'webm'];
                                                                    $explodeImage = explode('.', $photo->file_path);
                                                                    $extension = end($explodeImage);
                                                                ?>
                                                                @if(in_array($extension,$imageExtensions))
                                                                    <img src="{{asset('public/activityFiles/'.$photo->file_path)}}" width="300" alt="{{$photo->name}}" title="{{$photo->title}}" class="img img-responsive"/><br><br>
                                                                @elseif(in_array($extension,$videoExtensions))
                                                                    <video width="280" height="200" controls>
                                                                        <source src="{{asset('public/activityFiles/'.$photo->file_path)}}" type="video/mp4">
{{--                                                                            <source src="{{asset('public/activityFiles/'.$photo->file_path)}}" type="video/ogg">--}}
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                @endif
                                                                <h6 class="">{!! $photo->description !!}</h6>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-10 col-lg-10">
                                <div class="pull-right" >
                                    <div class="col-md-2">
                                        <button class="btn btn-primary " onclick="exportToExcel()">Export
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary " onclick="printProgress()" style="margin-left: 200px;">Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/jquery.table2excel.min.js')}}"></script>
{{--    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>--}}
{{--    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>--}}
{{--    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>--}}
    
    <script src="{{asset('public/js/jQuery.print.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 1 // Options | Number of years to show
            });
        });

        function printProgress(){
            $('.main-table').print({
                exclude: ".noExl",
            });
        }
        
        function exportToExcel() {
            $(".main-table").table2excel({
                exclude: ".noExl",
                name: "DailyProgressReport",
                filename: "daily-progress-{{ $project->project_code }}-"+ $(this).find('.progress_date').html() +".xlsx",
                // fileext: ".xls",
                // exclude_img: true,
                // exclude_links: true,
                // exclude_inputs: true
            });
        }

        function exportToExcel2(div_id) {
            $(div_id).table2excel({
                table: "#main",
                exclude: ".noExl",
                name: "DailyProgressReport",
                filename: "daily-progress-" + div_id + "{{ $project->project_code }} - {{ $nep_date ?? ''}}",
                fileext: ".xls",
                exclude_img: true,
                exclude_links: true,
                exclude_inputs: true
            });
            /*$('table').tableExport({
                filename: 'reconstruction-update.xls'
            });*/
        }


        function print() {
            var divToPrint = document.getElementsByClassName("detail");
            newWin = window.open("");
            newWin.document.write(divToPrint[0].outerHTML);
            newWin.print();
            newWin.close();
        }
    </script>

@stop