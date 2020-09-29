@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
    <link rel="stylesheet" href="{{asset('public/admin/plugin/token_input_bootstrap/css/bootstrap-tokenfield.min.css')}}" type="text/css" />
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('joint_venture.index')}}"><i class="fa fa-buysellads"></i> Joint Venture</a></li>
                    <li class="active"><a href="{{route('joint_venture.create')}}"><i class="fa fa-plus"></i> Joint Venture</a></li>
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('joint_venture.index')}}" class="btn btn-info showToolTip" title="Show Joint Ventures" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Joint Venture</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($jointVenture, ['route'=>['joint_venture.update', $jointVenture->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add Joint Venture</h3>
                                {!! minimizeButton('pro_collapse_jv') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_jv">
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','Name:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
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

                                <!-- nep_name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('nep_name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('nep_name','नाम:') !!}
                                        {!! Form::text('nep_name', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('nep_name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('nep_name') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nep_nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- address -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('address')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('address','Address:') !!}
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
                                <!-- email -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('email')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('email')) title="{!!$errors->first('email')!!}" @endif>
                                        {!! Form::label('email','Email:') !!}
                                        {!! Form::text('email', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('email'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="emailStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="emailStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- mobile -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('mobile')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('mobile')) title="{!!$errors->first('mobile')!!}" @endif>
                                        {!! Form::label('mobile','Mobile:') !!}
                                        {!! Form::text('mobile', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('mobile'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="mobileStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="mobileStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- fax -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('fax')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('fax')) title="{!!$errors->first('fax')!!}" @endif>
                                        {!! Form::label('fax','Fax:') !!}
                                        {!! Form::text('fax', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('fax'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="faxStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="faxStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- contractors -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('contractors')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('contractors','Contractors:') !!}
                                        {!! Form::text('contractors', $contractorsHere, ['class'=>'form-control contractors']) !!}
                                        @if($errors->has('contractors'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="contractorsStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('contractors') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="contractorsStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>4]) !!}
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
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    Projects</h3>
                                @if(count($jointVenture->procurements)>0)
                                    {!! minimizeButton('pro_authorized_project_list') !!}
                                @else
                                    <div class="clearfix"></div>
                                @endif
                            </div>
                            <div class="panel-body collapse" style="position: relative; top:0; right:0;" id="pro_authorized_project_list">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th>Name</th>
                                        <th>Project Code</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($jointVenture->procurements as $procurement)
                                        <tr>
                                            <td>{{ $procurement->project->name }}</td>
                                            <td>{{ $procurement->project->project_code }}</td>
                                            <td><a class="btn btn-success  btn-xs" href="{{ route('project.show',$procurement->project->id) }}">See More</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span> Publish</h3>
                                {!! minimizeButton('pro_collapse_jv_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_jv_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color" checked="checked" />
                                    <label for="status">Is Active?</label>
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
                    <div class="container"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footerContent')
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input/src/jquery.tokeninput.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/plugin/token_input_bootstrap/bootstrap-tokenfield.min.js')}}"></script>
    <script>
        var $source = [ @foreach($contractors as $contractor) {!! '"'.$contractor->name.'",' !!}   @endforeach ];
        var $contractors = $('.contractors');
        $contractors.tokenfield({
            autocomplete: {
                source: $source,
                delay: 100
            },
            createTokensOnBlur:true,
            showAutocompleteOnFocus: false,

        });
        preventDuplicate($contractors);

    </script>
@stop