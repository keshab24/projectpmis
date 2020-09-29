@extends('layouts.admin_layout')
@section('headerContent')
    <link href="{{ asset('public/admin/css/pro_map.css') }}" rel="stylesheet" type="text/css">
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
                    </li>
                    <li><a href="{{route('project.index')}}"><i class="fa fa-buysellads"></i> Project</a></li>
                    <li class="active"><a href="{{route('project.edit', $procurement->id)}}"><i
                                    class="fa fa-edit"></i> Edit Project</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('project.index')}}" class="btn btn-info showToolTip"
                           title="Edit Project" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Projects</span></a>
                        <a href="{{route('project.create')}}" class="btn btn-warning showToolTip"
                           title="Add Project" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-edit"></span> <span class="hidden-xs hidden-sm">Edit Project</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($procurement, ['route'=>['project.update', $procurement->id],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}

                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add
                                    Project</h3>
                                {!! minimizeButton('pro_collapse_project') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_project">
                                <h1>Project Name : {{ $procurement->project }}</h1>

                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','कार्यक्रम/आयोजनाको नाम:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control focus_field']) !!}
                                        @if($errors->has('name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('name') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- name_eng -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name_eng')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name_eng','Project Name:') !!}
                                        {!! Form::text('name_eng', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('name_eng'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('name_eng') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="name_engStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>




                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-map-marker"></span>
                                    Coordinates</h3>
                                {!! minimizeButton('pro_collapse_port_map') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_port_map">
                                <!-- for coordinates -->
                                <div class="form-group input-group @if(count($errors->all())>0){{($errors->has('coordinates'))?'has-error':'has-success'}}@endif col-lg-12 col-md-12">
                                    <input type='hidden' size='38' maxlength='40' name='coordinates' id='coordinates'
                                           value='27.700855895121396, 85.28978346679685' class="form-control" readonly/>

                                    @if($errors->has('coordinates'))
                                        <span class="glyphicon glyphicon-exclamation-sign form-control-feedback"
                                              aria-hidden="true"></span>
                                        <p class="help-block">{{$errors->first('coordinates')}}</p>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok form-control-feedback"
                                              aria-hidden="true"></span>
                                    @endif
                                </div>
                                <input id="pac-input" class="controls" type="text" placeholder="Search Place">

                                <div id='map' class='mapCooL' style="width:100%; height:370px;"></div>
                                <!-- for coordinates ends -->
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Other Details</h3>
                                {!! minimizeButton('pro_collapse_project_other') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_project_other">
                                <!-- headquarter -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('headquarter')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('headquarter','सदरमुकाममा रहेको:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('headquarter', array('थाहा नभएको','होइन','हो'), null, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('headquarter'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="headquarterStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('headquarter') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="headquarterStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- story_area_unite -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('story_area_unite')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('story_area_unite','भवनको तल्ला:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('story_area_unite', getStoreyArea(), $procurement->story_area_unite, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('story_area_unite'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="story_area_uniteStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('story_area_unite') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="story_area_uniteStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- pr_code -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('pr_code','भवनको क्षत्रफल:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('pr_code', null, ['class'=>'form-control focus_field']) !!}</div>
                                        @if($errors->has('pr_code'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="pr_codeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('pr_code') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="pr_codeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- land_ownership  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('land_ownership')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Land Ownership','लाल पुर्जा:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('land_ownership', array('0'=>'थाहा नभएको','1'=>'नभएको','2'=>'भएको'), null, ['id'=>'land_ownership','class'=>'form-control land_ownership']) !!}</div>
                                        @if($errors->has('land_ownership'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="land_ownershipStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('land_ownership') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="land_ownershipStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- swamittwo  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('swamittwo')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Swamittwi','स्वमित्त्व:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('swamittwo', swamittwo(), null, ['class'=>'form-control swamittwo']) !!}</div>
                                        @if($errors->has('swamittwo'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="swamittwoStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('swamittwo') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="swamittwoStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- kittanumber  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('kittanumber')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Kitta Number','कित्ता नम्बर:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('kittanumber', null, ['class'=>'form-control','id'=>'kittanumber']) !!}</div>
                                        @if($errors->has('kittanumber'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="kittanumberStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('kittanumber') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="kittanumberStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- swamittwo  -->
                                <div class="form-group col-md-6 col-lg-6  @if($errors->has('whose')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                       <div class="col-md-4">{!! Form::label('Whose','कस्को स्वमित्त्व:') !!}</div>
                                       <div class="col-md-8">{!! Form::select('whose', jaggaType(), null, ['class'=>'form-control','id'=>'whose']) !!}</div>
                                       @if($errors->has('whose'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="whoseStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('whose') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="whoseStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- shitnumber  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('shitnumber')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Sheet Number','शीट नम्बर:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('shitnumber', null, ['class'=>'form-control','id'=>'shitnumber']) !!}</div>
                                        @if($errors->has('shitnumber'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="shitnumberStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('shitnumber') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="shitnumberStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- bstype  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('bstype')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Bs Type','Bs Type:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('bstype', null, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('bstype'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="bstypeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('bstype') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="bstypeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- soiltest  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('soiltest')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                       <div class="col-md-4">{!! Form::label('Soil Test','माटो जाच:') !!}</div>
                                       <div class="col-md-8">{!! Form::select('soiltest', array('0'=>'थाहा नभएको','2'=>'भएको','1'=>'नभएको'), 0, ['id'=>'soiltest','class'=>'form-control']) !!}</div>
                                        @if($errors->has('soiltest'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="soiltestStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('soiltest') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="soiltestStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- baringcapacity  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('baringcapacity')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Bearing Capacity','Baring Capacity:') !!}</div>
                                        <div class="col-md-8">{!! Form::text('baringcapacity', null, ['id'=>'baringcapacity','class'=>'form-control']) !!}</div>
                                        @if($errors->has('baringcapacity'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="baringcapacityStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('baringcapacity') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="baringcapacityStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- rooftype  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('rooftype')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        <div class="col-md-4">{!! Form::label('Roof Type','छानाको प्रकृति:') !!}</div>
                                        <div class="col-md-8">{!! Form::select('rooftype', rooftype(),$procurement->rooftype, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('rooftype'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="rooftypeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('rooftype') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="rooftypeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- doorwindow  -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('doorwindow')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                       <div class="col-md-4">{!! Form::label('Door Windor','झ्याल ढोका:') !!}</div>
                                       <div class="col-md-8">{!! Form::select('doorwindow', doorWindow(),$procurement->doorWindor, ['class'=>'form-control']) !!}</div>
                                        @if($errors->has('doorwindow'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="doorwindowStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('doorwindow') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="doorwindowStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
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
    <script type="text/javascript" src="{{asset('public/admin/js/menu/menu.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

        });
    </script>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJQaz6AqTOGmByI43EqJd0SNEWz11FFjY&libraries=places"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/google_map.js')}}"></script>
@stop