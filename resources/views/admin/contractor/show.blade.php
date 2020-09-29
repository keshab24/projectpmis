@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span>
                            Dashboard</a></li>
                    <li><a href="{{route('contractor.index')}}"><i class="fa fa-buysellads"></i> Contractor</a>
                    </li>
                    <li class="active"><a href="{{route('contractor.create')}}"><i class="fa fa-plus"></i>
                            Contractor</a></li>
                </ol>
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('contractor.index')}}" class="btn btn-info showToolTip"
                           title="Edit Contractor" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Contractors</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    Contractor
                                </h3>
                                {!! minimizeButton('pro_collapse_contractor') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_contractor">
                                <h1>{{$contractor->name}}</h1>
                                <div class="clearfix"></div>
                                <hr>
                                <p>
                                  Email:  {{ ($contractor->email) }}
                                </p>
                                <p>
                                    Mobile:  {{ ($contractor->mobile) }}
                                </p>
                                <hr>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    Authorized Person</h3>
                                @if(count($contractor->authorizedPerson)>0)
                                    {!! minimizeButton('pro_authorized_person_list') !!}
                                @else
                                    <div class="clearfix"></div>
                                @endif
                            </div>
                            <div class="panel-body collapse" style="position: relative; top:0; right:0;" id="pro_authorized_person_list">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>

                                    @foreach($contractor->authorizedPerson->sortByDesc('id') as $person)
                                        <tr>
                                            <td>{{ $person->name }}</td>
                                            <td>{{ $person->email }}</td>
                                            <td>{{ $person->phone }}</td>
                                            <td><a class="btn btn-success  btn-xs" href="{{ route('authorized_person.edit',$person->slug) }}">See More</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    Projects</h3>
                                @if(count($contractor->procurements)>0)
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
                                    @foreach($contractor->procurements as $procurement)
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
                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight">
                        @if($contractor->myUser)
                            <div class="panel panel-default">
                                {!!Form::open(['route'=>'notice.store','method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                        Send Notification</h3>
                                    {!! minimizeButton('pro_collapse_contractor_save') !!}
                                </div>
                                <div class="panel-body collapse in" id="pro_collapse_contractor_save">
                                    <!-- title -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip" @if($errors->has('name')) title="{!!$errors->first('name')!!}" @endif>
                                            {!! Form::label('name','Name:') !!}
                                            {!! Form::text('name', null, ['class'=>'form-control firstInput','required'=>'required']) !!}
                                            @if($errors->has('name'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="nameStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="nameStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip" @if($errors->has('description')) title="{!!$errors->first('description')!!}" @endif>
                                            {!! Form::label('description','Description:') !!}
                                            {!! Form::textarea('description', null, ['class'=>'form-control ckeditor', 'rows'=>9]) !!}
                                            @if($errors->has('description'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="descriptionStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="descriptionStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="listener" value="contractor" id="contractor"/>
                                    <input type="hidden" name="listener_id" value="{{ $contractor->myUser->id }}" id="contractor"/>
                                </div>
                                <div class="panel-footer">
                                    <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                        Save
                                    </button>
                                    <button class="btn btn-default" type="reset">Reset</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        @endif
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-file"></i> Authorized Person (Create New)</h3>
                                {!! minimizeButton('pro_collapse_authorized_person') !!}
                            </div>
                            {!!Form::open(['route'=>['authorized_person.store'],'method'=>'Post','class'=>'showSavingOnSubmit'])!!}
                            <div class="panel-body collapse" id="pro_collapse_authorized_person">
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','Name') !!}
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
                                <input type="hidden" name="status" value="on">
                                <input type="hidden" name="contractor_id" value="{{ $contractor->id }}">
                                <!-- nep_name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('nep_name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('nep_name','Nepali name') !!}
                                        {!! Form::text('nep_name', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('nep_name'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nep_nameStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('nep_name') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="nep_nameStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- email -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('email')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('email','Email') !!}
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
                                <!-- phone -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('phone')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('phone','Phone') !!}
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
                                <!-- mobile -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('mobile')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('mobile','Mobile') !!}
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
                                <!-- fax -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('fax')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('fax','Fax') !!}
                                        {!! Form::text('fax', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('fax'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="faxStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('fax') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="faxStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- type -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('type')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('type','Designation') !!}
                                        <?php 
                                            if($contractor->type == 2)
                                                $types = consultantMemberTypes();
                                            else
                                                $types = contractorAuthorizedPersonTypes();
                                         ?>
                                        {!! Form::select('type', $types, null, ['class'=>'form-control']) !!}
                                        @if($errors->has('type'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="typeStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('type') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                            <span id="typeStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                    Upload
                                </button>
                                <button class="btn btn-default" type="reset">Reset</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-file"></i> Authorized Person (Select From Old)</h3>
                                {!! minimizeButton('pro_collapse_authorized_person_sync') !!}
                            </div>
                            {!!Form::open(['route'=>['project.authorized_person.sync',$contractor->slug],'method'=>'Post','class'=>'showSavingOnSubmit'])!!}
                            <div class="panel-body collapse" id="pro_collapse_authorized_person_sync">
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('','') !!}
                                        {!! Form::select('authorizedPersons[]',$authorizedPersons, $contractor->authorizedPerson->pluck('id')->toArray(), ['multiple'=>'multiple','class'=>'form-control']) !!}
                                        @if($errors->has(''))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="Status" class="sr-only">(error)</span>
                                        <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('') !!}</small></div>
                                        @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                        <span id="Status" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                    Upload
                                </button>
                                <button class="btn btn-default" type="reset">Reset</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('footerContent')
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/single_image/single_image.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".pro_images_browser").sortable({
                tolerance: 'pointer',
                revert: 'invalid',
                placeholder: '',
                forceHelperSize: true
            });
        });
    </script>
    <script type="text/javascript" src="{!! asset('public/admin/js/media/media.js') !!}"></script>
@stop