@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link href="{{ asset('public/admin/css/pro_map.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css" />
@stop
@section('content')
    <?php $thisDistricts=$implementingoffice->districts()->where('fy_id', session()->get('pro_fiscal_year'))->get()->toArray();
    $show="";
    ?>
    @foreach($thisDistricts as $thisDistrict)
        <?php $show = $thisDistrict['name_eng'].','.$show ?>
    @endforeach
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('implementingoffice.index')}}"><i class="fa fa-buysellads"></i> Implementing Office</a></li>
                    <li class="active"><a href="{{route('implementingoffice.edit', $implementingoffice->id)}}"><i class="fa fa-edit"></i> Edit Implementing Office</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('implementingoffice.index')}}" class="btn btn-info showToolTip" title="Edit Implementing Office" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Implementing Offices</span></a>
                        <a href="{{route('implementingoffice.create')}}" class="btn btn-warning showToolTip" title="Edit Implementing Office" role="button" data-placement="top"><span class="glyphicon glyphicon-edit"></span> <span class="hidden-xs hidden-sm">Add Implementing Office</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($implementingoffice, ['route'=>['implementingoffice.update', $implementingoffice->id],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Edit Implementing Office</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice">
                                <!-- division_code -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('district_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('district_id','Implementiong Office Name:') !!}
                                        {!! Form::select('district_id', $districts, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('district_id'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="district_idStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('district_id') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="district_idStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display: none">
                                    {!! Form::label('districts_eng','Implementiong Office Name:') !!}
                                    {!! Form::select('districts_eng', $districts_eng, null, ['class'=>'form-control']) !!}
                                </div>
                                <!-- districts -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('districts')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('districts','Districts:') !!}
                                        {!! Form::text('districts', $show, ['class'=>'form-control']) !!}
                                        @if($errors->has('districts'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="districtsStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('districts') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="districtsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','अायाेजना लागु हुने कार्यालयकाे नाम:') !!}
                                        {!! Form::text('name', $implementingoffice->getOriginal('name'), ['class'=>'form-control']) !!}
                                        @if($errors->has('name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('name') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- name_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name_eng','Implementing Office Name:') !!}
                                        {!! Form::text('name_eng', $implementingoffice->getOriginal('name_eng'), ['class'=>'form-control']) !!}
                                        @if($errors->has('name_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('name_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- description -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('description'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('description') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- description_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('description_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description_eng','Description(English):') !!}
                                        {!! Form::textarea('description_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('description_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="description_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('description_eng') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="description_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--<div class="panel panel-default address_block">--}}
                            {{--<div class="panel-heading">--}}
                                {{--<h3 class="panel-title pull-left"><span class="fa fa-bank"></span> Bank Detail</h3>--}}
                                {{--{!! minimizeButton('pro_collapse_add_save') !!}--}}
                            {{--</div>--}}
                            {{--<div class="panel-body collapse in" id="pro_collapse_add_save">--}}
                                {{--<!-- bank_name -->--}}
                                {{--<div class="form-group col-md-4 col-lg-4 @if($errors->has('bank_name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">--}}
                                    {{--<div class="input-group pro_make_full showToolTip">--}}
                                        {{--{!! Form::label('bank_name','Bank Name:') !!}--}}
                                        {{--{!! Form::text('bank_name', null, ['class'=>'form-control']) !!}--}}
                                        {{--@if($errors->has('bank_name'))--}}
                                            {{--<span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="bank_nameStatus" class="sr-only">(error)</span>--}}
                                            {{--<div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('bank_name') !!}</small></div>--}}
                                        {{--@elseif(count($errors->all())>0)--}}
                                            {{--<span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="bank_nameStatus" class="sr-only">(success)</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<!-- branch_address -->--}}
                                {{--<div class="form-group col-md-4 col-lg-4 @if($errors->has('branch_address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">--}}
                                    {{--<div class="input-group pro_make_full showToolTip">--}}
                                        {{--{!! Form::label('branch_address','Branch Address:') !!}--}}
                                        {{--{!! Form::text('branch_address', null, ['class'=>'form-control']) !!}--}}
                                        {{--@if($errors->has('branch_address'))--}}
                                            {{--<span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="branch_addressStatus" class="sr-only">(error)</span>--}}
                                            {{--<div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('branch_address') !!}</small></div>--}}
                                        {{--@elseif(count($errors->all())>0)--}}
                                            {{--<span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="branch_addressStatus" class="sr-only">(success)</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<!-- account_no -->--}}
                                {{--<div class="form-group col-md-4 col-lg-4 @if($errors->has('account_no')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">--}}
                                    {{--<div class="input-group pro_make_full showToolTip">--}}
                                        {{--{!! Form::label('account_no','Account No:') !!}--}}
                                        {{--{!! Form::text('account_no', null, ['class'=>'form-control']) !!}--}}
                                        {{--@if($errors->has('account_no'))--}}
                                            {{--<span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="account_noStatus" class="sr-only">(error)</span>--}}
                                            {{--<div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('account_no') !!}</small></div>--}}
                                        {{--@elseif(count($errors->all())>0)--}}
                                            {{--<span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="account_noStatus" class="sr-only">(success)</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="panel panel-default address_block">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Manage Address</h3>
                                {!! minimizeButton('pro_collapse_add_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_add_save">

                                <!-- district_id -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('division_code')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('division_code','Choose Division Name (District):') !!}
                                        {!! Form::select('division_code', $districts, null, ['class'=>'form-control','disabled'=>'disabled']) !!}
                                        <div style="display:none">
                                            {!! Form::select('division_code_eng', $districts_eng, null,['id'=>'division_code_eng']) !!}
                                        </div>
                                        @if($errors->has('division_code'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="division_codeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('division_code') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="division_codeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- address -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('address','Full Address:') !!}
                                        {!! Form::text('address', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('address'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="addressStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('address') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="addressStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- phone -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('phone')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('phone','Phone:') !!}
                                        {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('phone'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="phoneStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('phone') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="phoneStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- email -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('email')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('email','Email Address:') !!}
                                        {!! Form::text('email', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('email'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="emailStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('email') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="emailStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- chief -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('chief')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('chief','Office Chief:') !!}
                                        {!! Form::select('cheif_id', $cheifs, $cheif, ['class'=>'form-control']) !!}
                                        @if($errors->has('chief'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="chiefStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('chief') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="chiefStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- mobile -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('mobile')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('mobile','Mobile No:') !!}
                                        {!! Form::text('mobile', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('mobile'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="mobileStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('mobile') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="mobileStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-map-marker"></span> Coordinates</h3>
                                {!! minimizeButton('pro_collapse_port_map') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_port_map">
                                <!-- for coordinates -->
                                <div class="form-group input-group @if(count($errors->all())>0){{($errors->has('coordinates'))?'has-error':'has-success'}}@endif col-lg-12 col-md-12">
                                    <input type='hidden' size='38' maxlength='40' name='coordinates' id='coordinates' value='{!! preg_replace('/[^0-9\,\.]/','',$implementingoffice->coordinates) !!}' class="form-control" readonly />

                                    @if($errors->has('coordinates'))
                                        <span class="glyphicon glyphicon-exclamation-sign form-control-feedback" aria-hidden="true"></span>
                                        <p class="help-block">{{$errors->first('coordinates')}}</p>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
                                    @endif
                                </div>
                                <input id="pac-input" class="controls" type="text" placeholder="Search Place">
                                <div id='map' class='mapCooL' style="width:100%; height:370px;"></div>
                                <!-- for coordinates ends -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-th-large"></span> Choose Parent Implement Office <span class="glyphicon glyphicon-info-sign showInfo faa-bounce animated-hover" title="Choose Parent Office" data-content="Choose the office under which this office exists" data-trigger="focus" data-placement="top" data-toggle="popover" tabindex="0" role="button"></span></h3>
                                {!! minimizeButton('pro_collapse_tree') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_tree">
                                <ul class="tree">
                                    <li>
                                        <div class="click_menu @if($implementingoffice->parent_id == 1)click_menu_active @endif" data-menu-id="1" data-menu-level="0" data-menu-order="0">
                                            <span class="glyphicon glyphicon-th"></span> Root
                                        </div>
                                        <ul>
                                            @foreach($implementing_offices as $implementing_office)
                                                @if($implementing_office->level == 1)
                                                    <li>
                                                        <div class="click_menu @if($implementingoffice->parent_id == $implementing_office->id)click_menu_active @endif" data-menu-id="{!! $implementing_office['id'] !!}" data-menu-level="{!! $implementing_office['level'] !!}" data-menu-order="{!! $implementing_office['order'] !!}">
                                                            <span class="glyphicon glyphicon-arrow-right"></span> {!! $implementing_office['name'] !!}
                                                        </div>
                                                        @if(count($implementing_office->child)>0)
                                                            <ul>
                                                                @foreach($implementing_office->child as $implementing_office)
                                                                    @if($implementing_office->level == 2)
                                                                        <li>
                                                                            <div class="click_menu @if($implementingoffice->parent_id == $implementing_office->id)click_menu_active @endif" data-menu-id="{!! $implementing_office['id'] !!}" data-menu-level="{!! $implementing_office['level'] !!}" data-menu-order="{!! $implementing_office['order'] !!}">
                                                                                <span class="glyphicon glyphicon-arrow-right"></span> {!! $implementing_office['name'] !!}
                                                                            </div>
                                                                            @if(count($implementing_office->child)>0)
                                                                                <ul>
                                                                                    @foreach($implementing_office->child as $implementing_office)
                                                                                        @if($implementing_office->level == 3)
                                                                                            <li>
                                                                                                <div class="click_menu @if($implementingoffice->parent_id == $implementing_office->id)click_menu_active @endif" data-menu-id="{!! $implementing_office['id'] !!}" data-menu-level="{!! $implementing_office['level'] !!}" data-menu-order="{!! $implementing_office['order'] !!}">
                                                                                                    <span class="glyphicon glyphicon-arrow-right"></span> {!! $implementing_office['name'] !!}
                                                                                                </div>
                                                                                                @if(count($implementing_office->child)>0)
                                                                                                    <ul>
                                                                                                        @foreach($implementing_office->child as $implementing_office)
                                                                                                            @if($implementing_office->level == 4)
                                                                                                                <li>
                                                                                                                    <div class="click_menu" data-menu-id="{!! $implementing_office['id'] !!}" data-menu-level="{!! $implementing_office['level'] !!}" data-menu-order="{!! $implementing_office['order'] !!}">
                                                                                                                        <span class="glyphicon glyphicon-arrow-right"></span> {!! $implementing_office['name'] !!}
                                                                                                                    </div>
                                                                                                                </li>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                @endif
                                                                                            </li>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                                <br />
                                <br />
                                <!-- order -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('order')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group col-md-12 col-lg-12 showToolTip" @if($errors->has('order')) title="{!!$errors->first('order')!!}" @endif>
                                        {!!Form::label('order','Order:')!!}
                                        {!!Form::text('order', 0, ['class'=>'form-control','id'=>'menu_order'])!!}
                                        @if($errors->has('order'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="orderStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="orderStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- office_code -->
                                {{--<div class="form-group col-md-6 col-lg-6 @if($errors->has('office_code')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">--}}
                                    {{--<div class="input-group pro_make_full showToolTip">--}}
                                        {{--{!! Form::label('office_code','Office Code:') !!}<span class="glyphicon glyphicon-info-sign showInfo faa-bounce animated-hover" title="Write Custom Code" data-content="If you wish to provide custom code for office, you may write here" data-trigger="focus" data-placement="top" data-toggle="popover" tabindex="0" role="button"></span>--}}
                                        {{--{!! Form::text('office_code', null, ['class'=>'form-control']) !!}--}}
                                        {{--@if($errors->has('office_code'))--}}
                                            {{--<span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="office_codeStatus" class="sr-only">(error)</span>--}}
                                            {{--<div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('office_code') !!}</small></div>--}}
                                        {{--@elseif(count($errors->all())>0)--}}
                                            {{--<span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>--}}
                                            {{--<span id="office_codeStatus" class="sr-only">(success)</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {!! Form::hidden('implementing_office_id',$implementingoffice->parent_id,['id'=>'implementing_office_id']) !!}
                                {!! Form::hidden('level',$implementingoffice->level,['id'=>'level']) !!}
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" @if($implementingoffice->status == 1)checked="checked"@endif />
                                    <label for="status">Is Active?</label>
                                </div>
                                <!-- is_monitoring -->
                                <div class="form-group col-md-12 col-lg-12 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="is_monitoring" id="is_monitoring" class="third" @if($implementingoffice->isMonitoring == 1)checked="checked"@endif/>
                                    <label for="is_monitoring">Is Monitoring?</label>
                                </div>
                                <!-- is_physical_office -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="is_physical_office" id="is_physical_office" class="second_color" @if($implementingoffice->is_physical_office == 1)checked="checked"@endif/>
                                    <label for="is_physical_office">Is Physical Office?</label>
                                </div>

                                <!-- is_last_node -->
                                <div class="form-group col-md-12 col-lg-12 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="is_last_node" id="is_last_node" class="first_color" @if($implementingoffice->is_last_node == 1)checked="checked"@endif />
                                    <label for="is_last_node">Is Last Node? <small><i class="fa fa-info-circle"></i>Check this if this is last level office</small></label>
                                </div>



                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save</button>
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
    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJQaz6AqTOGmByI43EqJd0SNEWz11FFjY&libraries=places"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/google_map.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $('#division_code').val($('#district_id').find(":selected").val()).change();
            $('#district_id').select2();
            var $source = [<?php foreach($districts as $district) echo '"'.$district.'",'; ?>];
            var $districts = $('#districts');
            $districts.tokenfield({
                autocomplete: {
                    source: $source,
                    delay: 100
                },
                createTokensOnBlur:true,
                showAutocompleteOnFocus: true
            });
            preventDuplicate($districts);
        });
        $('#district_id').change(function(){
            var district_name=($('#district_id').find(":selected").text());
            $('#division_code').val($('#district_id').find(":selected").val()).change();
            $('#districts_eng').val($('#district_id').find(":selected").val()).change();
            var district_eng=($('#districts_eng').find(":selected").text());
            $('#name').val('डीभीजन कार्यालय, '+district_name);
            $('#name_eng').val('Divsion Office, '+district_eng);
        });
    </script>
@stop