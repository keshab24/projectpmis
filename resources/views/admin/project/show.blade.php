@extends('layouts.admin_layout')
@section('headerContent')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .deleteText{
            visibility: hidden;
        }
        .delete :hover .deleteText{
            visibility: visible;
        }
        @media all and (min-width: 480px) {
             .pro_images_section label,  .pro_files_section label {
                margin-top: 0%;
                height: 50px;
                border: dashed 2px #aaaaaa;
                background: #efefef;
                /* display: inherit; */
                margin-bottom: 20px;
                text-align: center;
                cursor: -webkit-grab;
                cursor: grab;
            }
            .photo_label{
                margin-top: 0%;
            }
             .pro_images_section label:hover,  .pro_files_section label:hover {
                background: #dddddd;
            }
             .pro_images_section label:active,  .pro_files_section label:active {
                cursor: -webkit-grabbing;
                cursor: grabbing;
            }

            .pro_images_section input[type=file],  .pro_files_section input[type=file] {
                display: none;
            }
             .pro_images_section label.noBorder,  .pro_files_section label.noBorder {
                border: none;
            }
             .pro_images_section label span,  .pro_files_section label span {
                margin-top: 10px;
            }
             .pro_images_section label img,  .pro_files_section label img {
                max-width: 100%;
                max-height: 100%;
            }
             
             .pro_images_section input[type=button],  .pro_files_section input[type=button] {
                position: absolute;
                top: 0;
            }
             .pro_images_section input,  .pro_files_section input {
                margin-bottom: 10px;
            }
             .pro_images_section .file_icon,  .pro_files_section .file_icon {
                color: #387fb9;
                padding-top: 32px;
            }
             .pro_images_section .i_file_name,  .pro_files_section .i_file_name {
                font-size: 12px;
                overflow: hidden;
            }
             .pro_images_section .iframePdf,  .pro_files_section .iframePdf {
                width: 100%;
                height: 100%;
            }
}
    </style>
@stop

@section('content')
    @php
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
    @endphp
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li><a href="{{route('project.index')}}"><i class="fa fa-buysellads"></i> Projects</a></li>
                    <li class="active"><a href="{{route('project.show',$project->id)}}"><i class="fa fa-edit"></i> {{$project->name}}</a></li>

                    <li>
                        <a title="Insert daily Progress" href="{{ route('daily.progress', $project->id).'?today' }}">
                            <i class="fa fa-plus"></i>
                            Insert Today's Daily Progress
                        </a>
                    </li>
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('project.index')}}/search?search={{ $setting->project_code}}"
                           class="btn btn-warning showToolTip"
                           title="Back To Index" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-chevron-left"></span><span class="hidden-xs hidden-sm"> Back</span></a>
                        <a href="{{route('project.index')}}" class="btn btn-info showToolTip"
                           title="View All Projects" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Projects</span></a>
                    </div>
                    <div class="col-md-1 col-lg-1 pull-right">
                        <a class="btn btn-xs btn-primary" title="Logs"
                           href="{{ route('projectLogs',$project->id) }}"><span class="fa fa fa-th-list"></span> View
                            Log</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">

                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInRight"
                         data-appear-delay="1200">
                        {{--Daily Progress Human Resource Management--}}
                        {!!Form::open(['route'=>['project.engineers.add', $project->id],'method'=>'POST','class'=>'showSavingOnSubmit'])!!}
                            @foreach(manpowerTypes() as $index=>$manpower_type)
                            <div  {{ restrictToImplementingOffice() }} class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><i class="fa fa-file"></i>{{$manpower_type}}</h3>
                                    {!! minimizeButton('pro_project_engineers'.$index) !!}
                                </div>

                                <div class="panel-body collapse" id="pro_project_engineers{{$index}}">
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('manpower')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('manpower', 'Manpower Title') !!}
                                            {!! Form::select('manpower', ['human_resources'] ,['selected_human_resources'], ['class'=>'form-control','multiple'=>'multiple']) !!}
                                            @if($errors->has('manpower'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="manpowerStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="manpowerStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success" >
                                        Upload
                                    </button>
                                    <button class="btn btn-default" type="reset">Reset</button>
                                </div>
                            </div>
                            @endforeach
                        {!! Form::close() !!}

                        {{--Daily Progress User Management--}}
                        @if(Auth::user()->implementing_office_id === 410)
                            <div  {{ restrictToImplementingOffice() }} class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><i class="fa fa-file"></i> Daily Progress User Management</h3>
                                    {!! minimizeButton('pro_project_daily_progress_users') !!}
                                </div>
                                {!!Form::open(['route'=>['project.daily-progress.user.add', $project->id],'method'=>'POST','class'=>'showSavingOnSubmit'])!!}
                                <div class="panel-body collapse" id="pro_project_daily_progress_users">
                                    <!-- human resources -->
                                    <?php
                                    $selectedUsers = $project->DailyProgressUsers()->get();
                                    $selectedUsers = $selectedUsers->pluck('id')->toArray();
                                    ?>
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('users')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('users','Users') !!}
                                            {!! Form::select('users[]', $dailyProgressUsers ,$selectedUsers, ['class'=>'form-control','multiple'=>'multiple']) !!}
                                            @if($errors->has('users'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="project_managersStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger">
                                                    <small><i class="fa fa-warning"></i> {!! $errors->first('users') !!}
                                                    </small>
                                                </div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                      aria-hidden="true"></span>
                                                <span id="project_managersStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/editor.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/file.js')}}"></script>
    
    <script type="text/javascript">
        $('.nepaliDate').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10 // Options | Number of years to show
        });
        
        $('document').ready(function () {
            $('#cancelled_modal').on('hidden.bs.modal', function (e) {
                cancelProjectCancellation()
            })
            
            $('#cancelled').on('change', function (e) {
                if (e.target.checked) {
                    $('#cancelled_modal').modal();
                }
            });
            
            $('#cancelledForm').submit(function (e) {
                $project_code = $('#project_code').val();

                if ($project_code === $(this).attr('data-project-id')) {
                    $('#project_code_error_on_finish').hide();
                    cancelled_date = $('#cancelled_date').val();
                    if (cancelled_date == '' || cancelled_date == '0000-00-00') {
                        $('#cancelled_date_error_on_finish').show();
                        $('#cancelled_date_error_on_finish').html('Enter Valid Date');
                        return false;
                    } else {
                        $('#cancelled_date_error_on_finish').hide();
                    }
                    cancelled_remarks = $('#cancelled_remarks').val();
                    if (cancelled_remarks == '' || cancelled_remarks == '0000-00-00') {
                        $('#cancelled_remarks_error_on_finish').show();
                        $('#cancelled_remarks_error_on_finish').html('Please Enter Project Cancelled remark');
                        return false;
                    } else {
                        $('#cancelled_remarks_error_on_finish').hide();
                    }
                } else {
                    $('#project_code_error_on_finish').show();
                    $('#project_code_error_on_finish').html('Enter Valid Project Code to Proceed');
                    return false;
                }
                return true
            })
        });

        function cancelProjectCancellation() {
            $('#cancelled').prop('checked', false)
        }
    </script>

    <script type="text/javascript">
        $('.confirm-block-delete').on('click', function () {
            return confirm('Are you sure want to delete block?');
        });
        $('.confirm-file-delete').on('click', function () {
            return confirm('Are you sure want to delete this file?');
        });

        var y = 24;
        function addFiles(input, index){
                $(input).closest('.pro_files_browser').append('<div class=" col-md-4 col-lg-4 pro_files_section">' +
                    '                                                    <span class="fa fa-times-circle closeButton"' +
                    '                                                              onclick="hideFilesDiv($(this))"></span>' +
                    '                                                    <label class="photo_label" for="file'+y+'"><span class="fa fa-plus-square"></span> Add a' +
                    '                                                        File</label>' +
                    '                                                    <input type="file" name="file'+index+'[]" onchange="readFileURL(this)" index="'+index+'"' +
                    '                                                           class="form-control" id="file'+y+'" multiple />' +
                    '                                                    <input type="text" style="width: 75px; font-size: small;" name="file'+index+'_date[]" class="nepaliDate"' +
                    '                                                           id="'+y+'" value="{{ str_replace("/","-",dateBS(date("Y-m-d"))) }}">' +
                    '                                                    <div class="clearfix"></div>' +
                    '                                                </div>');
                $('.nepaliDate').nepaliDatePicker({
                    npdMonth: true,
                    npdYear: true,
                    npdYearCount: 10 // Options | Number of years to show
                });
                $('.nepaliDate').attr('autocomplete', 'off');
                
                y++
        }
    </script>
@stop


