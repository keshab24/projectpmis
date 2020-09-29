@extends('layouts.admin_layout')


@section('headerContent')
    <link rel="stylesheet"
          href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/admin/plugin/lightbox/css/lightbox.css')}}" type="text/css"/>
    <style>
        .select_contractor {
            width: 20em;
        }

        .table-wrapper {
            overflow-x: scroll;
            overflow-y: scroll;
            margin-left: 250px;
        }


        td, th {
            /*padding: 5px 20px;*/
            /*width: 300px;*/
        }

        tbody tr {

        }

        .table-hover th:first-child {
            width: 250px;
            position: absolute;
            left: 5px
        }

        .table-hover td:first-child {
            width: 250px;
            position: absolute;
            left: 5px
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
                    <li class="active"><a href="{{route('procurement.index')}}"><span
                                    class="glyphicon glyphicon-blackboard"></span> Procurement</a></li>
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            <a href="{{route('project.index')}}" class="btn btn-info showToolTip"
                               title="View All Projects" role="button" data-placement="top"><span
                                        class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Projects</span></a>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            {!! Form::open(['route'=>'searchProcurement','method'=>'get']) !!}
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    @if($user_info->implementingOffice->is_new_town)
                                        {{--new town project--}}
                                        {!! Form::select('implementing_office_id', $implementing_offices,isset($implementing_office)?$implementing_office:0, ['class'=>'form-control']) !!}
                                    @else
                                        {{--piu ma child piu office haru lai grouping garera dekhaune.--}}
                                        {!! piuOfficesSelectList($implementing_offices_new_update, isset($implementing_office)?$implementing_office:null,'implementing_office_id') !!}
                                    @endif
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    {!! Form::select('fiscal_year', $fiscal_years,isset($fiscal_year)?$fiscal_year:0, ['class'=>'form-control']) !!}
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    {!! Form::select('budgettopic', $budgettopics,isset($budgettopic)?$budgettopic:0, ['class'=>'form-control']) !!}
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    {!! Form::select('expendituretopic', $expendituretopics,isset($expendituretopic)?$expendituretopic:0, ['class'=>'form-control']) !!}
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    {!! Form::select('limit',$limits,$limit,['class'=>'form-control smallText pro_submit_form']) !!}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{$default_search}}"
                                               class="form-control smallText" placeholder="Search Procurements...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit"><span
                                                        class="fa fa-filter"></span> <span class="hidden-xs hidden-sm">Filter!</span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <p> Showing {{ $projects->firstItem() }}
                        to {{ $projects->lastItem() }} of {{ $totalProjects }} entries </p>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">

                    <div class="table-responsive table-wrapper pro_hide_y">
                        {!! Form::model($projects,['route'=>['procurement.update','1=1'],'method'=>'put', 'class'=>'showSavingOnSubmit']) !!}
                        <table class="table table-striped table-hover">
                            <tr class="smallText">
                                <th class="fixed" width="3%">
                                    अायाेजना
                                </th>
                                <th class="fixed">
                                    Type
                                </th>
                                <th width="5%">
                                    Method
                                </th>
                                <th width="8%">
                                    Contractor Type
                                </th>
                                <th width="8%">
                                    Contractor
                                </th>
                                <th width="3%">
                                    Design Approved Date
                                </th>
                                <th width="3%" class="text-danger" >
                                    Initial Project Cost
                                </th>
                                <th width="3%" class="text-danger" >
                                    Total Liability
                                </th>
                                <th width="3%" >
                                    Estimate Amount
                                </th>
                                <th width="3%">
                                    Estimate Approved date
                                </th>
                                <th width="3%">
                                    Bid Does Ready
                                </th>
                                <th width="3%">
                                    No Objection 1
                                </th>
                                <th width="3%">
                                    Call For Bid
                                </th>
                                <th width="3%">
                                    Bid Open
                                </th>
                                <th width="3%">
                                    Bid Evaluation
                                </th>
                                <th width="3%">
                                    No Objection 2
                                </th>
                                <th width="3%">
                                    Bolapatra Swikriti
                                </th>
                                <th width="3%" >
                                    Contract Amount
                                </th>
                                <th width="3%">
                                    Contract Date
                                </th>
                                <th width="3%">
                                    Work Order Date
                                </th>
                                <th width="3%">
                                    End Date
                                </th>
                                <th width="3%">
                                    Contract ID (DIVISION)
                                </th>
                                <th width="3%">
                                    Contract ID (World Bank)
                                </th>
                                <th width="3%">
                                    Remarks
                                </th>
                                <th width="3%">
                                    Verified
                                </th>
                                <th width="3%">
                                    Mode
                                </th>
                                <th width="3%">Contingency (%)</th>
                                <th width="3%">
                                    Status
                                </th>
                            </tr>

                            @if($projects->isEmpty())
                                <tr>
                                    <td colspan="12">
                                        <div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i>
                                            No Procurement Found
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <?php $i = 1; ?>
                                @foreach($projects as $index => $project)
                                <!--                                        --><?php //$procurement = false; ?>
                                    {{--@if($project->procurement)--}}
                                    <?php $procurement = $project->procurement; ?>
                                    {{--@endif--}}
                                    <tr class="@if(isset($_GET['trashes'])) danger @endif smallText">
                                        <td class="fixed" style="text-align: left;">{!! $project->name !!}
                                            <span class="project_code">
                                                ({{$project->project_code}})
                                            </span>
                                        </td>
                                        <td class="fixed">
                                            EST
                                            <br>
                                            <hr>
                                            <br>
                                            ACT
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::select('method[]', getPurchaseMethod(), $procurement->method, ['class'=>'method']) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            <select name="jv_selector[]" class="jv_selector">
                                                <option value="1">
                                                    JV
                                                </option>
                                                <option @if($procurement?$procurement->joint_venture==null:'') selected="selected"
                                                        @endif value="0">
                                                    Contractor
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            <span class="contractor"
                                                  @if($procurement?$procurement->joint_venture!=null:'') style="display: none" @endif >
                                                {!! Form::select('contractors[]', $contractors, $procurement?$procurement->contractor:'', ['class'=>'select_contractor']) !!}
                                            </span> <span
                                                    @if($procurement?$procurement->joint_venture==null:'') style="display: none"
                                                    @endif  class="joint_ventures">
                                               {!! Form::select('joint_venture[]', $joinVentures, $procurement?$procurement->joint_venture:'', ['class'=>'select_contractor']) !!}
                                           </span>
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::text('design_est_swikrit_miti[]', $procurement?$procurement->design_est_swikrit_miti:'',['class'=>' smallText nepaliDate','placeholder'=>'Design Approved Date','id'=>'design_est_swikrit_miti'.$index]) !!}
                                        </td>
                                        <td >
                                            {!! Form::text('estimated_amount[]', $procurement?$procurement->estimated_amount:'',['class'=>' smallText','placeholder'=>'Initial Project Cost','disabled']) !!}
                                        </td>
                                        <td >
                                            <br>
                                            <br>
                                            {!! Form::text('total_liability[]', $procurement?$procurement->total_liability:'',['class'=>' smallText','placeholder'=>'Total Liability']) !!}
                                        </td>
                                        <td >
                                            <br>
                                            <br>
                                            {!! Form::text('con_est_amt_net[]', $procurement?$procurement->con_est_amt_net:"",['class'=>' smallText','placeholder'=>'Estimate Amount']) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::text('est_approved_date[]', $procurement?$procurement->est_approved_date:"",['class'=>" smallText nepaliDate",'placeholder'=>'Estimate Approved date','id'=>'est_approved_date'.$index]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('bid_does_ready_est[]', $procurement?$procurement->bid_does_ready_est:"",['class'=>' smallText nepaliDate','placeholder'=>'Bid Does Ready Estimate','id'=>'bid_does_ready_est'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('bid_does_ready_act[]', $procurement?$procurement->bid_does_ready_act:"",['class'=>' smallText nepaliDate','placeholder'=>'Bid Does Ready Act','id'=>'bid_does_ready_act'.$index]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('no_obj_est1[]', $procurement?$procurement->no_obj_est1:"",['class'=>' smallText nepaliDate','placeholder'=>'No objection 1 Estimate','id'=>'no_obj_est1'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('no_obj_act1[]', $procurement?$procurement->no_obj_act1:"",['class'=>' smallText nepaliDate','placeholder'=>'No objection 1 Act','id'=>'No objection 1 Act'.$index]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('call_for_bid_est[]', $procurement?$procurement->call_for_bid_est:"",['class'=>' smallText nepaliDate','placeholder'=>'Call For Bid Estimate','id'=>'call_for_bid_est'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('call_for_bid_act[]', $procurement?$procurement->call_for_bid_act:"",['class'=>' smallText nepaliDate','placeholder'=>'Call  For Bid Act','id'=>'call_for_bid_act'.$index]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('bid_open_est[]', $procurement?$procurement->bid_open_est:"",['class'=>' smallText nepaliDate','placeholder'=>'Bid Open Estimate','id'=>'bid_open_est'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('bid_open_act[]', $procurement?$procurement->bid_open_act:"",['class'=>' smallText nepaliDate','placeholder'=>'Bid Open Act','id'=>'bid_open_act'.$index]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('bid_eval_est[]', $procurement?$procurement->bid_eval_est:"",['class'=>' smallText nepaliDate','placeholder'=>'Bid evaluation Estimate','id'=>'bid_eval_est'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('bid_eval_act[]', $procurement?$procurement->bid_eval_act:"",['class'=>' smallText nepaliDate','placeholder'=>'Bid Evaluation Act','id'=>'bid_eval_act'.$index]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('no_obj_est2[]', $procurement?$procurement->no_obj_est2:"",['class'=>' smallText nepaliDate','placeholder'=>'No Objection 2 Estimate','id'=>'no_obj_est2'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('no_obj_act2[]', $procurement?$procurement->no_obj_act2:"",['class'=>' smallText nepaliDate','placeholder'=>'No Objection 2 Act','id'=>'no_obj_act2'.$index]) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::text('bolapatraswikriti[]', $procurement?$procurement->bolapatraswikriti:"",['class'=>' smallText nepaliDate','placeholder'=>'Bolapatra Swikriti','id'=>'bolapatraswikriti'.$index]) !!}
                                        </td>
                                        <td >
                                            <br>
                                            <br>
                                            {!! Form::text('contract_amount[]', $procurement?$procurement->contract_amount:"",['class'=>' smallText','placeholder'=>'Contract Amount']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('con_sign_est[]', $procurement?$procurement->con_sign_est:"",['class'=>' smallText nepaliDate','placeholder'=>'Contract Sign Estimate','id'=>'con_sign_est'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('contract_date[]', $procurement?$procurement->contract_date:"",['class'=>' smallText nepaliDate','placeholder'=>'Contract Date','id'=>'contract_date'.$index]) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::text('wo_date[]', $procurement?$procurement->wo_date:"",['class'=>' smallText nepaliDate','placeholder'=>'Work Order Date','id'=>'wo_date'.$index]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('con_end_est[]', $procurement?$procurement->con_end_est:"",['class'=>' smallText nepaliDate','placeholder'=>'Contract End Estimate','id'=>'con_end_est'.$index]) !!}
                                            <br>
                                            <br>
                                            {!! Form::text('completion_date[]', $procurement?$procurement->completion_date:"",['class'=>' smallText nepaliDate','placeholder'=>'Completion Date','id'=>'completion_date'.$index]) !!}
                                        </td>

                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::text('con_id_div[]', $procurement?$procurement->con_id_div:"",['class'=>' smallText','placeholder'=>'Contractor Id (Division)','style'=>'width:220px']) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::text('con_id_web[]', $procurement?$procurement->con_id_web:"",['class'=>' smallText','placeholder'=>'Contractor Id (World Bank)','style'=>'width:220px']) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::text('remarks[]', $procurement?$procurement->remarks:"",['class'=>' smallText','placeholder'=>'Remarks']) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::select('verified[]',array('Yes','No'),$procurement?$procurement->verified:1,['class'=>' smallText']) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::select('implementing_mode_id[]',$implementing_modes,$procurement?$procurement->implementing_mode_id:"",['class'=>' smallText']) !!}
                                        </td>
                                        <td>
                                            <br>
                                            <br>
                                            {!! Form::input('number','contingency[]',$procurement->contingency,['class'=>' smallText']) !!}
                                        </td>
                                        <td>
                                            @if($project->procurement)
                                                @if($project->procurement->status==1)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">In-Active</span>
                                                @endif
                                            @else
                                                <span class="label label-default">Not Defined</span>
                                            @endif
                                        </td>

                                    </tr>
                                    {!! Form::hidden('project_code[]',$project->id) !!}
                                @endforeach
                                <tr>
                                    <td colspan="32">
                                        <button type="submit" class="btn btn-sm btn-warning showToolTip"
                                                title="Save/Update"><span class="fa fa-floppy-o"> Save</span>
                                        </button>
                                    </td>
                                </tr>

                            @endif
                        </table>

                        {!! Form::close() !!}
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            {!! massAction('','local','Procurement') !!}
                        </div>
                    </div>
                    <p class="text-center"> Showing {{ $projects->firstItem() }}
                        to {{ $projects->lastItem() }} of {{ $totalProjects }} entries </p>

                    <div class="pull-right">
                        {!! str_replace('/?', '?', $projects->appends(Request::input())->render()) !!}
                    </div>
                    {{--                    {!! $projects->appends(Request::all())->render() !!}--}}
                </div>

            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/lightbox/js/lightbox.js')}}"></script>
    <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input/src/jquery.tokeninput.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script type="text/javascript">
        var reload = false;
        $(document).ready(function () {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            pro_search_project();
            $("#pro_implementing_office_id").change(function () {
                reload = true;
                pro_search_project();
            });

        });
        $('.jv_selector').on('change', '', function (e) {
            if (this.value == 1) {
                $(this).parent().next().find('.joint_ventures').show();
                $(this).parent().next().find('.contractor').hide();
            } else {
                $(this).parent().next().find('.contractor').show();
                $(this).parent().next().find('.joint_ventures').hide();
            }
        });

        function pro_search_project() {
            var $io = $("#pro_implementing_office_id");
            var $division_code = $("#pro_helper_form .pro_division_code");
            if ($io.val() == 1) {
                $division_code.fadeIn();
                $division_code.removeAttr('disabled', 'disabled');
            } else {
                $division_code.fadeOut();
                $division_code.attr('disabled', 'disabled');
                if (reload)
                    $("#pro_helper_form").trigger('submit');
            }
        }

        $('input').keyup(function (e) {
            if (e.which == 40)
                $(this).siblings(":last").focus();
            else if (e.which == 38)
                $(this).siblings(":first").focus();
        });

    </script>
@stop