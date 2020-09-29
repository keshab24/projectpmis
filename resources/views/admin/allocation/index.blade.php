@extends('layouts.admin_layout')


@section('headerContent')
<link rel="stylesheet" href="{{asset('public/admin/plugin/lightbox/css/lightbox.css')}}" type="text/css" />
@stop

@section('content')
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                <li class="active"><a href="{{route('allocation.index')}}"><span class="glyphicon glyphicon-blackboard"></span> Allocation</a></li>

            </ol>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p> Showing {{ $projects->firstItem() }}
                to {{ $projects->lastItem() }} of {{ $projects->total() }} entries </p>
            @if($projects_info)
                <?php
                $total = 0;
                $total += $gon = $lump_sum->gon - $lump_sum->gon_exp;
                $total += $loan = $lump_sum->loan - $lump_sum->loan_exp;

                $total += $grants = $lump_sum->grants - $lump_sum->grants_exp;
                $total += $direct = $lump_sum->direct_payments - $lump_sum->direct_payments_exp;

                $tabindex = 1;
                $pro_total_available_budget = $total;
                $pro_gon_available_budget = $gon;
                $pro_loan_available_budget = $loan;
                $pro_grants_available_budget = $grants;
                $pro_direct_available_budget = $direct;
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <?php $allocation = false; $amendment = false; ?>
                        @if($projects_info->allocation->count() > 0)
                            <?php

                                $allocation = $projects_info->allocation->where('fy_id',session()->get('pro_fiscal_year'))->sortByDesc('amendment')->first();
                                $amendment = $allocation->amendment;

                                $pro_total_available_budget = $total + $allocation->total_budget;
                                $pro_gon_available_budget = $gon + $allocation->gon;
                                $pro_loan_available_budget = $loan + $allocation->loan;
                                $pro_grants_available_budget = $grants + $allocation->grants;
                                $pro_direct_available_budget = $direct + $allocation->direct;
                            ?>
                            @if(isset($amend))
                                {!! Form::model($allocation,['route'=>['allocation.store'],'method'=>'post','name'=>'pro_budget_allocation_form','id'=>'pro_budget_allocation_form']) !!}
                                {!! Form::hidden('amendment',$amendment+1) !!}
                                {!! Form::hidden('amend','true') !!}
                            @else
                                {!! Form::model($allocation,['route'=>['allocation.update',$allocation->id],'method'=>'put','name'=>'pro_budget_allocation_form','id'=>'pro_budget_allocation_form']) !!}
                            @endif
                        @else
                        {!! Form::open(['route'=>'allocation.store','method'=>'post','name'=>'pro_budget_allocation_form','id'=>'pro_budget_allocation_form']) !!}
                            {!! Form::hidden('amendment','0') !!}
                        @endif
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-money"></span>
                                    @if($projects_info->allocation->count() > 0)
                                        @if(isset($amend))
                                            {{$am = ($amendment +1)}}<?php
                                            if($am==1)
                                                echo "<sup>st</sup>";
                                            elseif($am==2)
                                                echo "<sup>nd</sup>";
                                            elseif($am==3)
                                                echo "<sup>rd</sup>";
                                            else
                                                echo "<sup>th</sup>";
                                            ?>
                                            Amendment
                                        @else
                                            Edit Allocated Budget
                                            @if($projects_info->allocation->count() > 1)
                                                ({{$am = $amendment}}
                                                <?php
                                                if($am==1)
                                                    echo "<sup>st</sup>";
                                                elseif($am==2)
                                                    echo "<sup>nd</sup>";
                                                elseif($am==3)
                                                    echo "<sup>rd</sup>";
                                                else
                                                    echo "<sup>th</sup>";
                                                ?>
                                                Amendment)
                                            @endif
                                        @endif
                                    @else
                                        Budget Allocation
                                    @endif
                                </h3>
                                {!! minimizeButton('pro_allocation_form') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_allocation_form">
                                {!! Form::hidden('budget_topic_id_for_next_search', $projects_info->budget_topic->id, ['class'=>'form-control']) !!}

                                <!-- total_budget -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('total_budget')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('total_budget','Total Budget:') !!}
                                        {!! Form::text('total_budget', null, ['class'=>'form-control focus_field','tabindex'=>$tabindex++]) !!}
                                        @if($errors->has('total_budget'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="total_budgetStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('total_budget') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="total_budgetStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- first_trim -->
                                <div class="form-group col-md-4 col-lg-4 @if($errors->has('first_trim')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('first_trim','First Trim:') !!}
                                        <div class="input-group">
                                            {!! Form::text('first_trim', null, ['class'=>'form-control pro_trimester', 'tabindex'=>$tabindex++]) !!}
                                            <span class="input-group-addon">
                                                <input type="checkbox" name="first_trim_per" id="first_trim_per" class="pro_trim_check">
                                            </span>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">%</button>
                                            </div>
                                        </div>
                                        @if($errors->has('first_trim'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="first_trimStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('first_trim') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="first_trimStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- second_trim -->
                                <div class="form-group col-md-4 col-lg-4 @if($errors->has('second_trim')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('second_trim','Second Trim:') !!}
                                        <div class="input-group">
                                            {!! Form::text('second_trim', null, ['class'=>'form-control pro_trimester','tabindex'=>$tabindex++]) !!}
                                            <span class="input-group-addon">
                                                <input type="checkbox"  class="pro_trim_check" name="second_trim_per" id="second_trim_per">
                                            </span>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">%</button>
                                            </div>
                                        </div>
                                        @if($errors->has('second_trim'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="second_trimStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('second_trim') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="second_trimStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- third_trim -->
                                <div class="form-group col-md-4 col-lg-4 @if($errors->has('third_trim')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('third_trim','Third Trim:') !!}
                                        <div class="input-group">
                                            {!! Form::text('third_trim', null, ['class'=>'form-control pro_trimester','tabindex'=>$tabindex++]) !!}
                                            <span class="input-group-addon">
                                                <input type="checkbox"  class="pro_trim_check" name="third_trim_per" id="third_trim_per">
                                            </span>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">%</button>
                                            </div>
                                        </div>
                                        @if($errors->has('third_trim'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="third_trimStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('third_trim') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="third_trimStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-money"></span> स्राेतगत बाँडफाँड</h3>
                                {!! minimizeButton('pro_sources') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_sources">
                                <!-- gon -->
                                <div class="form-group col-md-4 col-lg-4 @if($errors->has('gon')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('gon','नेपाल सरकार') !!}
                                        <div class="input-group">
                                            {!! Form::text('gon', null, ['class'=>'form-control pro_budget_source','tabindex'=>$tabindex++]) !!}
                                            <span class="input-group-addon">
                                                <input type="checkbox" name="gon_per" id="gon_per" class="pro_source_check">
                                            </span>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">%</button>
                                            </div>
                                        </div>
                                        @if($errors->has('gon'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="gonStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('gon') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="gonStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- loan -->
                                <div class="form-group col-md-4 col-lg-4 @if($errors->has('loan')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('loan','Loan:') !!}
                                        <div class="input-group">
                                            {!! Form::text('loan', null, ['class'=>'form-control pro_budget_source','tabindex'=>$tabindex++]) !!}
                                            <span class="input-group-addon">
                                                <input type="checkbox" name="loan_per" id="loan_per" class="pro_source_check" >
                                            </span>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">%</button>
                                            </div>
                                        </div>
                                        @if($errors->has('loan'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="loanStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('loan') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="loanStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- grants -->
                                <div class="form-group col-md-4 col-lg-4 @if($errors->has('grants')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('grants','Grants:') !!}
                                        <div class="input-group">
                                            {!! Form::text('grants', null, ['class'=>'form-control pro_budget_source','tabindex'=>$tabindex++]) !!}
                                            <span class="input-group-addon">
                                                <input type="checkbox" name="grants_per" id="grants_per" class="pro_source_check" >
                                            </span>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">%</button>
                                            </div>
                                        </div>
                                        @if($errors->has('grants'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="grantsStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('grants') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="grantsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- direct -->
                                <div class="form-group col-md-4 col-lg-4 @if($errors->has('direct')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('direct','Direct Payment:') !!}
                                        <div class="input-group">
                                            {!! Form::text('direct', null, ['class'=>'form-control pro_budget_source','tabindex'=>$tabindex++]) !!}
                                            <span class="input-group-addon">
                                                <input type="checkbox" name="direct_per" id="direct_per" class="pro_source_check">
                                            </span>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">%</button>
                                            </div>
                                        </div>
                                        @if($errors->has('direct'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="directStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('direct') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="directStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-money"></span> संशोधित विवरण</h3>
                                {!! minimizeButton('pro_amendment') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_amendment">

                                <!-- amended_date -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('amended_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('amended_date')) title="{!!$errors->first('amended_date')!!}" @endif>
                                        {!! Form::label('amended_date','संशोधित मिति') !!}
                                        <input class="form-control" id="amended_date" type="date" name="amended_date" value="@if($allocation) {{$allocation->amended_date}} @endif"/>
                                        @if($errors->has('amended_date'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="amended_dateStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="amended_dateStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- remarks -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('remarks')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip" @if($errors->has('remarks')) title="{!!$errors->first('remarks')!!}" @endif>
                                        {!! Form::label('remarks','संशोधन गर्नुपर्नाको औचित्य') !!}
                                        {!! Form::text('remarks', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('remarks'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="remarksStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="remarksStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="panel-footer col-md-12">
                                    <button type="submit" class="btn btn-success btn-md pull-right" data-loading-text="Saving/Updating..." autocomplete="off">Save/Update</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::hidden('project_code',$projects_info->id) !!}
                        <?php if(isset($_GET['implementing_office_id'])){
                            $implementing_office_id = $_GET['implementing_office_id']; ?>
                            {!! Form::hidden('implementing_office_id',$implementing_office_id) !!}
                        <?php } ?>
                        <?php if(isset($_GET['division_code'])){
                            $division_code = $_GET['division_code']; ?>
                            {!! Form::hidden('division_code',$division_code) !!}
                        <?php } ?>
                        {!! Form::hidden('budget_topic_id',$projects_info->budget_topic_id) !!}
                        {!! Form::hidden('expenditure_topic_id',$projects_info->expenditure_topic_id) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-money"></span> Information</h3>
                                {!! minimizeButton('pro_budget_info') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_budget_info">

                                <table class="table table-striped table-hover">
                                    <tr class="info">
                                        <th colspan="2">
                                            <h3><span class="fa fa-bank"></span>
                                                {{ $projects_info->name }}
                                            </h3>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th> <span class="fa fa-briefcase"> बजेट शीर्षक </span> </th>
                                        <td> {{ $projects_info->budget_topic->budget_head }} ( {{ $projects_info->budget_topic->budget_topic_num }} ) </td>
                                    </tr>
                                    <tr>
                                        <th> <span class="fa fa-credit-card"> खर्च शीर्षक </span> </th>
                                        <td> {{ $projects_info->expenditure_topic->expenditure_head }} ( {{ $projects_info->expenditure_topic->expenditure_topic_num }} ) </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table class="table table-hover table-responsive table-condensed table-striped table-bordered">
                                                <tr>
                                                    <th>जम्मा बजेट</th>
                                                    <th>नेपाल सरकार</th>
                                                    <th>ऋण</th>
                                                    <th>अनुदान</th>
                                                    <th>सोझै भुक्तानी</th>
                                                </tr>
                                                <tr>
                                                    <td class="align-right">{{ number_format($total) }}</td>
                                                    <td class="align-right">{{ number_format($gon) }}</td>
                                                    <td class="align-right">{{ number_format($loan) }}</td>
                                                    <td class="align-right">{{ number_format($grants) }}</td>
                                                    <td class="align-right">{{ number_format($direct) }}</td>

                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <h4> Trimester Budget Guide</h4>
                                <hr/>
                                <div class="row">
                                    {!! Form::open(['route'=>'change.budget.guide','method'=>'post','name'=>'pro_budget_guide_form','id'=>'pro_budget_guide_form']) !!}
                                    <div class="col-md-3">
                                        <label for="first_trimester_percentage_guide">1 <sup>st</sup> Trim (%)</label>
                                        <input type="text" name="first_trimester_percentage_guide" class="form-control" value="@if(session()->has('first_trimester_percentage_guide')){{session()->get('first_trimester_percentage_guide')}}@else 20 @endif" />
                                    </div>
                                    <div class="col-md-3">
                                        <label for="second_trimester_percentage_guide">2 <sup>nd</sup> Trim (%)</label>
                                        <input type="text" name="second_trimester_percentage_guide" class="form-control" value="@if(session()->has('second_trimester_percentage_guide')){{session()->get('second_trimester_percentage_guide')}}@else 40 @endif" />
                                    </div>
                                    <div class="col-md-3">
                                        <label for="third_trimester_percentage_guide">3<sup>rd</sup> Trim (%)</label>
                                        <input type="text" name="third_trimester_percentage_guide" class="form-control" value="@if(session()->has('third_trimester_percentage_guide')){{session()->get('third_trimester_percentage_guide')}}@else 40 @endif" />
                                    </div>
                                    <div class="col-md-3"><br/><button role="submit" class="btn btn-success">Change</button></div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        {!! Form::open(['route'=>'allocation.index','method'=>'get','id'=>'pro_helper_form']) !!}
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="input-group">
                                    <input type="text" name="search" value="{{$default_search}}" class="form-control" placeholder="Search Allocations...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit"><span class="fa fa-filter"></span>  <span class="hidden-xs hidden-sm">Filter!</span></button>
                                        </span>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                {!! Form::select('limit',$limits,$limit,['class'=>'form-control pro_submit_form']) !!}
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                {!! Form::select('budget_topic_id',$budget_topics,$budget_topic_id,['class'=>'form-control', 'id'=>'pro_budget_topic_id']) !!}
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                {!! Form::select('expenditure_topic_id',$expenditure_topics,$expenditure_topic_id,['class'=>'form-control', 'id'=>'pro_expenditure_topic_id']) !!}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                               {{-- {!! Form::select('implementing_office_id',$implementing_offices,$implementing_office_id,['class'=>'form-control','id'=>'pro_implementing_office_id']) !!}--}}
                                @if($user_info->implementingOffice->is_new_town)
                                    {{--new town project--}}
                                    {!! Form::select('implementing_office_id', $implementing_offices,isset($implementing_office)?$implementing_office:0, ['class'=>'form-control']) !!}
                                @else
                                    {{--piu ma child piu office haru lai grouping garera dekhaune.--}}
                                    {!! piuOfficesSelectList($implementing_offices_new_update, isset($implementing_office)?$implementing_office:null,'implementing_office_id') !!}
                                @endif
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                {!! Form::select('division_code',$division_codes,$division_code,['class'=>'form-control pro_submit_form pro_hide_me pro_division_code','disabled'=>'disabled']) !!}
                            </div>
                            <span class="label label-info"> {{ $count .' out of '. $totalProjects.' filtered' }}</span>

                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="col-md-2 col-lg-2 col-sm-2">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <button class="btn btn-info showToolTip" title="Total Projects"><span class="badge">{{$total_items}}</span></button>
                                <a href="{{route('allocation.index')}}?trashes=yes" class="btn btn-danger showToolTip" title="Trashes" role="button"><span class="fa fa-trash"></span> <span class="badge">{{$trashes_no}}</span></a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                @if($not_found == false)
                <div class="table-responsive pro_hide_y">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th width="6%">
                                क्रम संख्या
                            </th>
                            <th width="6%">
                                ब.उ.शी.नं.
                            </th>
                            <th width="6%">
                                ख.शी.नं.
                            </th>
                            <th width="30%">
                                अायाेजना
                            </th>
                            <th width="13%">Last Amendment</th>
                            <th width="13%">Action</th>
                        </tr>

                        @if($projects->isEmpty())
                        <tr>
                            <td colspan="12">
                                <div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i> Please add some allocations first!!</div>
                            </td>
                        </tr>
                        @else
                        <?php $i=1;
                        $pro_budget_topic = '';
                        $pro_exp_label = 'success';
                        ?>
                        @foreach($projects as $index=>$project)

                            @if($pro_budget_topic != $project->budget_topic->budget_topic_num)
                            <?php
                                $pro_exp_label= $pro_exp_label == 'success'?'info':'success';
                            ?>
                            @endif
                        <tr @if(isset($_GET['trashes'])) class="danger {{$pro_exp_label}}" @else class="{{$pro_exp_label}}" @endif>
                        <td>
                            {{ $index+1 }}
                        </td>

                        <td>
                            <?php
                            $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
                            if(!$setting){
                                $setting = $project;
                            }
                            ?>
                            @if($pro_budget_topic != $setting->budget_topic->budget_topic_num)
                            <?php
                            $pro_budget_topic = $setting->budget_topic->budget_topic_num;
                            ?>
                            {{$setting->budget_topic->budget_topic_num}}
                                                <span class="label label-{{$pro_exp_label}} label-xs">
                                                {{$project->budget_topic->budget_head}}
                                                </span>
                            @endif
                        </td>
                        <td>
                            {{ $setting->expenditure_topic->expenditure_topic_num }}
                            <span class="label label-{{$pro_exp_label}} label-xs">
                            {{ $setting->expenditure_topic->expenditure_head }}
                            </span>
                        </td>
                        <td>{!! $project->name !!}</td>
                        <td>
                            <?php
                                $count = $project->allocation()->where('fy_id',fiscalYearToday())->count()-1;
                            ?>
                            @if($count >0)
                            {!! $count !!}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($project->allocation->count()>0)
                                {!! Form::open(['route'=>'allocate','method'=>'get', 'class'=>'showSavingOnSubmit pro_form_inline']) !!}
                                {!! Form::hidden('project_code',$project->id) !!}
                                @if($budget_topic_id)
                                    {!! Form::hidden('budget_topic_id',$budget_topic_id) !!}
                                    @if($expenditure_topic_id)
                                        {!! Form::hidden('expenditure_topic_id',$expenditure_topic_id) !!}
                                    @endif
                                @endif

                                @if($implementing_office_id)
                                    {!! Form::hidden('implementing_office_id',$implementing_office_id) !!}
                                    @if($division_code)
                                        {!! Form::hidden('division_code',$division_code) !!}
                                    @endif
                                @endif
                                    <button type="submit" name="submit" title="Edit Allocation" class="btn btn-xs btn-success"><span class="label label-xs"><i class="fa fa-edit"></i></span></button>
                                {!! Form::close() !!}
                            @endif
                            {!! Form::open(['route'=>'allocate','method'=>'get', 'class'=>'showSavingOnSubmit pro_form_inline']) !!}
                                {!! Form::hidden('project_code',$project->id) !!}
                                @if($budget_topic_id)
                                    {!! Form::hidden('budget_topic_id',$budget_topic_id) !!}
                                    @if($expenditure_topic_id)
                                        {!! Form::hidden('expenditure_topic_id',$expenditure_topic_id) !!}
                                    @endif
                                @endif

                                @if($implementing_office_id)
                                    {!! Form::hidden('implementing_office_id',$implementing_office_id) !!}
                                    @if($division_code)
                                        {!! Form::hidden('division_code',$division_code) !!}
                                    @endif
                                @endif
                                @if(!$project->allocation->count() > 0)
                                <button type="submit" name="submit" title="Allocation" class="btn btn-xs btn-primary"><span class="label label-xs">Allocate</span></button>
                                @else
                                    {!! Form::hidden('amend','true') !!}
                                    <button type="submit" name="submit" title="Amendment" class="btn btn-xs btn-primary"><span class="label label-xs">Amend</span></button>
                                @endif
                            {!! Form::close() !!}

                        </td>
                        </tr>

                        @endforeach
                        @endif
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-6">
                        {!! massAction('','local','Allocation') !!}
                    </div>
                </div>
                    <p class="text-center"> Showing {{ $projects->firstItem() }}
                        to {{ $projects->lastItem() }} of {{ $projects->total() }} entries </p>
                    <div class="pull-right">
                        {!! str_replace('/?', '?', $projects->appends(Request::input())->render()) !!}
                    </div>
{{--                {!! str_replace('/?', '?', $projects->appends(Request::input())->render()) !!}--}}
                @else
                <div class="alert alert-danger">
                    <i class="fa fa-frown-o"></i> {{$not_found}} <a class="btn btn-xs btn-danger" href="{{route('allocation.index')}}">All</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('footerContent')
<script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/plugin/lightbox/js/lightbox.js')}}"></script>
<script type="text/javascript">
    var reload = false;
    $(document).ready(function(){
        $('#pro_budget_guide_form').submit(function(){
            var $total = 0;
           $('#pro_budget_guide_form input[type=text]').each(function(){
               $total += Number($(this).val());
           });
            if($total == 100) return true; else{
                $(this).after('<div class="clearfix"></div><br><div class="alert alert-danger">The sum of percentages for the three trimesters need to be exactly 100.</div>');
                return false;
            }
        });
        @if($projects_info)
            var $loan = {{($lump_sum->loan/$lump_sum->total_budget)}};
            var $grants = {{($lump_sum->grants/$lump_sum->total_budget)}};
        //alert({{$lump_sum->total_budget}});
            var $direct_payments = {{($lump_sum->direct_payments/$lump_sum->total_budget)}};
            var $gon = {{($lump_sum->gon/$lump_sum->total_budget)}};
            $('#total_budget').change(function(){
                //alert($loan);
                var $gon_value = $(this).val();
                $('#loan').val($(this).val()*$loan);
                $('#grants').val($(this).val()*$grants);
                $('#direct').val($(this).val()*$direct_payments);
                $('#gon').val($(this).val()*$gon);
                var $first_trim_percentage_guide = Number($('input[name=first_trimester_percentage_guide]').val())/100;
                var $second_trim_percentage_guide = Number($('input[name=second_trimester_percentage_guide]').val())/100;
                var $third_trim = $(this).val();
                $('#first_trim').val(Math.floor($(this).val()*$first_trim_percentage_guide));
                $third_trim -= Math.floor($(this).val()*$first_trim_percentage_guide)
                $('#second_trim').val(Math.floor($(this).val()*$second_trim_percentage_guide));
                $third_trim -= Math.floor($(this).val()*$second_trim_percentage_guide)
                $('#third_trim').val($third_trim);
            });
        @endif
        $("#pro_budget_allocation_form").submit(function(){
            var $total_trim = 0;
            //var $total_source = 0;
            var $error = new Array();
            $error[0] = 0;
            var $total_amount = Number($('#total_budget').val());
            if($('.pro_trim_check:first-child').is(":checked")){
                $('.pro_trimester').each(function(){
                   $total_trim += Number($(this).val());
                });
                if($total_trim != 100){
                    $error[0] = 1;
                    $error[1] = "The total of values in each trimester needs to be exactly 100%";
                    $error[2] = "#first_trim";
                }
            }else{
                $('.pro_trimester').each(function(){
                    $total_trim += Number($(this).val());
                });
                if($total_trim != $total_amount){
                    $error[0] = 1;
                    $error[1] = "The total of values in each trimester needs to be exactly "+ $total_amount;
                    $error[2] = "#first_trim";
                }
            }


            if($error[0] > 0)
            {
                $($error[2]).focus().select();
                swal($error[1], "Please correct and submit again!", "error");
                return false;
            }else{
                return true;
            }
        });

        $('.pro_trim_check').change(function(){
            if($(this).is(":checked")){
                $('.pro_trim_check').each(function(){
                   this.checked = true;
                });
            }else{
                $('.pro_trim_check').each(function(){
                    this.checked = false;
                });
            }
        });

        $('.pro_source_check').change(function(){
            if($(this).is(":checked")){
                $('.pro_source_check').each(function(){
                    this.checked = true;
                });
            }else{
                $('.pro_source_check').each(function(){
                    this.checked = false;
                });
            }
        });

        pro_search_project();
        $("#pro_budget_topic_id").change(function(){
           reload = true;
            pro_search_project();
        });
        $("#pro_expenditure_topic_id").change(function(){
            reload = true;
            pro_search_project();
        });
        $("#pro_implementing_office_id").change(function(){
            reload = true;
            pro_search_project();
        });
        @if($projects_info)
            <?php $total = $pro_total_available_budget;?>
            $("#total_budget").keyup(function () {
                var $total = Number({{ $total }});
                checkBudget($(this), $total);
            });
            $("#total_budget").blur(function () {
                var $total = Number({{ $total }});
                checkBudget($(this), $total);
            });
        @endif

    });
    function checkBudget($total_budget, $amount){
        var $total_current = Number($($total_budget).val());
        var $crossed = $total_current-$amount;
        if($amount<$total_current){
            var $msg = "Allocated budget has crossed the ceiling by Rs. "+$crossed;
            swal($msg,"Please correct and submit again!","error");
            $($total_budget).focus().select();
            return false;
        }
    }

    function pro_search_project(){
        var $io = $("#pro_implementing_office_id");
        var $division_code = $("#pro_helper_form .pro_division_code");
        if($io.val() == 1){
            $division_code.fadeIn();
            $division_code.removeAttr('disabled','disabled');
        }else{
            $division_code.fadeOut();
            $division_code.attr('disabled','disabled');
            if(reload)
                $("#pro_helper_form").trigger('submit');
        }
    }
</script>
@stop