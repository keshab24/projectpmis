@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css"/>
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span>
                            Dashboard</a></li>
                    <li><a href="{{route('employee.index')}}"><i class="fa fa-buysellads"></i> Employee</a>
                    </li>
                    <li class="active"><a href="{{route('employee.edit')}}"><i class="fa fa-edit"></i>
                            Edit Employee</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('employee.index')}}" class="btn btn-info showToolTip"
                           title="Edit Employee" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Employees</span></a>
                        <a href="{{route('employee.create')}}" class="btn btn-success showToolTip"
                           title="Add Budget Topic" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-edit"></span> <span class="hidden-xs hidden-sm">Add Employee</span></a>
                        <a href="{{route('employee.edit',$employee->slug)}}"
                           class="btn btn-warning showToolTip" title="Add Budget Topic" role="button"
                           data-placement="top"><span class="glyphicon glyphicon-edit"></span> <span
                                    class="hidden-xs hidden-sm">Edit Employee</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-8 col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    Employee</h3>
                                {!! minimizeButton('pro_collapse_employee_save') !!}
                            </div>

                            <div class="panel-body collapse in" id="pro_collapse_employee_save">
                                <h1>{{  $employee->name}}
                                    <small>- {{ $employee->designation->name}}</small>
                                </h1>
                                <hr>
                                <img width="60%" class="img img-circle col-lg-4 col-md-4 pull-left"
                                     src="{{asset('public/images/employee/'.$employee->image)}}"
                                     alt="{{$employee->name}}">

                                <div class="col-md-8 col-lg-8">
                                    <table class="table table-striped">
                                        <tr>
                                            <th class="col-lg-3 col-md-3">Address</th>
                                            <td>{{$employee->address.' , '.$employee->district->name_eng}}</td>
                                        </tr>
                                        <tr>
                                            <th class="col-lg-3 col-md-3">Marital Status</th>
                                            <td>{{($employee->marital_status == 0)?' Single' : ' Married'}}</td>
                                        </tr>
                                        <tr>
                                            <th class="col-lg-3 col-md-3">Phone/Mobile</th>
                                            <td> {{$employee->mobile.' / '.$employee->phone}}</td>
                                        </tr>
                                        <tr>
                                            <th class="col-lg-3 col-md-3">Date of Birth :</th>
                                            <td>{{$employee->date_of_join}}</td>
                                        </tr>
                                        <tr>
                                            <th class="col-lg-3 col-md-3">Joined at</th>
                                            <td>{{$employee->date_of_birth}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-dollar"></i> Salary Guide</h3>
                                {!! minimizeButton('pro_collapse_salaryGuide_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_salaryGuide_save">

                                {!!Form::open(['route'=>['employee.Salary.store',$employee->slug],'method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                                <table class="table table-responsive table-striped">
                                    <thead>
                                        <tr>
                                            <th>Salary Head</th>
                                            <th>Amount</th>
                                            <th>Updated Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $date_labels = '';
                                    ?>
                                    @foreach($salary_heads as $index=>$salary_head)
                                        <tr>
                                            <?php
                                            if($employee->tagged_salaries()->whereSalary_head_id($salary_head->id)->count()>0){
                                                $tagged_salary = $employee->tagged_salaries()->whereSalary_head_id($salary_head->id)->orderBy('id','desc')->firstOrFail();
                                            }
                                            ?>
                                            <td>
                                                {{$salary_head->name}}
                                                @if(isset($tagged_salary)) <br><span class="label label-xs label-default"><small>Last Updated {{$tagged_salary->date}}</small></span>@endif
                                            </td>
                                            <td>
                                                {!! Form::text('amount[]', isset($tagged_salary) ? $tagged_salary->amount:null, ['class'=>'form-control']) !!}
                                                {!! Form::hidden('salary_head_id[]', $salary_head->id) !!}
                                            </td>
                                            <td>
                                                <?php $comma = $index > 0?',':''; $date_labels .= $comma.'#'.$date_id = 'date'.$salary_head->id; ?>
                                                {!! Form::text('date[]', isset($tagged_salary) ? null:dateBS(date('Y-m-d')), ['class'=>'form-control','id'=>$date_id]) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                    Update
                                </button>
                                <button class="btn btn-default" type="reset">Reset</button>
                            </div>
                        </div>

                    </div>
                    {!! Form::close() !!}
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/plugin/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $('{{$date_labels}}').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
        });
    </script>
@stop