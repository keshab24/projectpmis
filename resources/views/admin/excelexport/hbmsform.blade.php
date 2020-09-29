@extends('layouts.admin_layout')

@section('headerContent')
    <style>
        .panel-body .btn{
            margin:2px;
        }
        .btn-holder{
            min-height: 100px;
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
                    <div class="row">
                        <div class="col-md-4">
                            {!!Form::open(['route'=>'hbms.excel.download','method'=>'POST','class'=>'showSavingOnSubmit','files'=>true])!!}
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="table-responsive pro_hide_y centered">
                                        <table class="table table-striped table-hover col-offset-6 centered">
                                            <tr>
                                                <td colspan="2">
                                                    @if($user_info->implementingOffice->isNewTown)
                                                        {!! Form::select('implementing_office_id',$implementingoffices,null,['class'=>'form-control']) !!}
                                                    @else
                                                        {!! piuOfficesSelectList($implementing_offices_after_update, isset($implementing_office) ? $implementing_office : null,'implementing_office_id') !!}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {!! Form::select('budget_topic_id',add_my_array($budgettopics),null,['class'=>'form-control','placeholder' => 'बजेट शीर्षक ']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('project_status',$statuses,4,['class'=>'form-control','id'=>'status']) !!}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    {!! Form::select('fiscal_year',$fy,0,['class'=>'form-control','id'=>'status','placeholder' => 'आ व']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('state_id',$states,0,['class'=>'form-control','id'=>'states','placeholder'=>'प्रदेश']) !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="btn-holder" id="div2" ondrop="drop(event)" ondragover="allowDrop(event)">
                                               @foreach(exportFields() as $index=>$field)
                                                   @if($field[3])
                                                       <div ondblclick="changePlace(event)" draggable="true" ondragstart="drag(event)" id="{{$index}}" class="btn-xs btn btn-info">
                                                           {{$field[0]}}
                                                           <input type="hidden" value="{{$index}}" name="displayField[]">
                                                       </div>
                                                   @endif
                                               @endforeach
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-footer">
                                            <input type="submit" class="btn btn-success">
                                            <a href="{{ route('hbms.excel.view') }}" class="btn btn-default">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12 col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div id="div1" class="btn-holder" ondrop="drop(event)" ondragover="allowDrop(event)">
                                            <h4>Procurement</h4>
                                            @foreach(exportFields() as $index=>$field)
                                               @if(!$field[3] && $field[5]=='section_procurement')
                                                    <div draggable="true" ondblclick="changePlace(event)" ondragstart="drag(event)" id="{{$index}}" class="btn-xs btn btn-danger">
                                                        {{$field[0]}}
                                                        <input type="hidden" value="{{$index}}" name="displayField[]">
                                                    </div>
                                                @endif
                                            @endforeach
                                            <hr>
                                            <h4>Project</h4>
                                            @foreach(exportFields() as $index=>$field)
                                                @if(!$field[3] && $field[5]=='section_project')
                                                    <div draggable="true" ondblclick="changePlace(event)" ondragstart="drag(event)" id="{{$index}}" class="btn-xs btn btn-danger">
                                                        {{$field[0]}}
                                                        <input type="hidden" value="{{$index}}" name="displayField[]">
                                                    </div>
                                                @endif
                                            @endforeach
                                            <hr>
                                            <h4>Group</h4>
                                            @foreach(exportFields() as $index=>$field)
                                                @if(!$field[3] && $field[5]=='section_group')
                                                    <div draggable="true" ondblclick="changePlace(event)" ondragstart="drag(event)" id="{{$index}}" class="btn-xs btn btn-danger">
                                                        {{$field[0]}}
                                                        <input type="hidden" value="{{$index}}" name="displayField[]">
                                                    </div>
                                                @endif
                                            @endforeach
                                            <hr>
                                            <h4>Budget And Expenditure</h4>
                                            @foreach(exportFields() as $index=>$field)
                                                @if(!$field[3] && $field[5]=='section_budget_expenditure')
                                                    <div draggable="true" ondblclick="changePlace(event)" ondragstart="drag(event)" id="{{$index}}" class="btn-xs btn btn-danger">
                                                        {{$field[0]}}
                                                        <input type="hidden" value="{{$index}}" name="displayField[]">
                                                    </div>
                                                @endif
                                            @endforeach
                                            <hr>
                                            <h4>Progress</h4>
                                            @foreach(exportFields() as $index=>$field)
                                                @if(!$field[3] && $field[5]=='section_progress')
                                                    <div draggable="true" ondblclick="changePlace(event)" ondragstart="drag(event)" id="{{$index}}" class="btn-xs btn btn-danger">
                                                        {{$field[0]}}
                                                        <input type="hidden" value="{{$index}}" name="displayField[]">
                                                    </div>
                                                @endif
                                            @endforeach
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript">
        $(document).ready(function(){
            @if(session()->has('lump_sum_budget_issue'))
                swal('Please set lump sum budget for \n Budget Topic No. {{session()->get('lump_sum_budget_issue')}}, first!!!');
            @endif
        });

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
        }

        function drop(ev) {
            ev.preventDefault();
            var data = ev.dataTransfer.getData("text");
            if(ev.target.id == 'div2' || ev.target.id == 'div1'){
                ev.target.appendChild(document.getElementById(data));
            }else{
                ev.target.parentNode.appendChild(document.getElementById(data));
            }

            alterClass(document.getElementById(data),ev.target.id);
        }

        function changePlace(ev) {
            ev.preventDefault();
            var moveTo='div2';
            var parentId=ev.target.parentNode.id;
            if(parentId == 'div2'){
                moveTo='div1';
            }
            $(ev.target).appendTo("#"+moveTo);
            alterClass(ev.target,moveTo);
        }

        function alterClass(object,target){
            if(target=="div1"){
                $(object).removeClass("btn-info").addClass( "btn-danger" );
            }else{
                $(object).removeClass("btn-danger").addClass( "btn-info" );

            }
        }
    </script>

@stop