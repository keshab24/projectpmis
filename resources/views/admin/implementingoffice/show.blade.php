@extends('layouts.admin_layout')
@section('headerContent')
    <link rel="stylesheet" href="{{asset('public/admin/plugin/select2/css/select2.min.css')}}" type="text/css" />
@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('implementingoffice.index')}}"><i class="fa fa-buysellads"></i> Implementing Office</a></li>
                    <li class="active"><a href="{{route('implementingoffice.edit',$implementingoffice->id)}}"><i class="fa fa-edit"></i> Edit Implementing Office</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route('implementingoffice.index')}}" class="btn btn-info showToolTip" title="Edit Implementing Office" role="button" data-placement="top"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Implementing Offices</span></a>
                        <a href="{{route('implementingoffice.create')}}" class="btn btn-success showToolTip" title="Add Budget Topic" role="button" data-placement="top"><span class="glyphicon glyphicon-edit"></span> <span class="hidden-xs hidden-sm">Add Implementing Office</span></a>
                        <a href="{{route('implementingoffice.edit',$implementingoffice->id)}}" class="btn btn-warning showToolTip" title="Add Budget Topic" role="button" data-placement="top"><span class="glyphicon glyphicon-edit"></span> <span class="hidden-xs hidden-sm">Edit Implementing Office</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-8 col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span> Implementing Office</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice_save">

                                <h1 class="pull-left">{{$implementingoffice->name}}</h1>
                                <h2 class="pull-right">@if($implementingoffice->level == 2) <small><a
                                                href="{{route('implementingoffice.show',$implementingoffice->parent->id)}}">{{$implementingoffice->parent->name_eng}}</a>
                                            > </small>@endif
                                        @if($implementingoffice->level == 3) <small><a
                                                href="{{route('implementingoffice.show',$implementingoffice->parent->parent->id)}}">{{$implementingoffice->parent->parent->name_eng}}</a>
                                            > <a
                                                href="{{route('implementingoffice.show',$implementingoffice->parent->id)}}">{{$implementingoffice->parent->name_eng}}</a>
                                            > </small>@endif
                                        @if($implementingoffice->level == 4) <small>{{$implementingoffice->parent->parent->parent->name_eng}}><a
                                                href="{{route('implementingoffice.show',$implementingoffice->parent->parent->parent->id)}}">{{$implementingoffice->parent->parent->name_eng}}</a>
                                        > <a
                                                href="{{route('implementingoffice.show',$implementingoffice->parent->parent->id)}}">{{$implementingoffice->parent->parent->name_eng}}</a>
                                        > <a
                                                href="{{route('implementingoffice.show',$implementingoffice->parent->id)}}">{{$implementingoffice->parent->name_eng}}</a>
                                            > </small>@endif</h2>
                                <div class="clearfix"></div>
                                <hr>
                                <p>
                                    {!! nl2br($implementingoffice->description) !!}
                                </p>
                                <hr>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-money"></i> पेस्की निकाशा तथा खर्चको विवरण</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice_nisaka') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice_nisaka">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>मिति</th>
                                        <th style="width: 25%;">कार्यक्रमहरु</th>
                                        <th>जम्मा रकम</th>
                                        <th style="width:20%;">भुक्तानी विवरण</th>
                                        <th>अवस्था</th>
                                        <th>आवश्यक कार्य</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{$payment->release_date}}</td>
                                                <td>
                                                    <ul>
                                                        @foreach($payment->release as $release)
                                                            <li>{{$release->project->name}}({{$release->project->construction_type->name}})</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    {{$payment->release()->sum('amount')}}
                                                </td>
                                                <td>
                                                    @if($payment->payment_method == 'FCGO')
                                                        {{$payment->payment_detail}}
                                                    @elseif($payment->payment_method == 'Account Payee')
                                                        माछापुच्छ्रे बैंक लिमिटेडको एकाउन्ट पेयी चेक नं {{$payment->cheque_no}}
                                                    @else
                                                        बैंक डिपोजिट
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment->status == 0)
                                                        <span class="label label-danger">Pending</span>
                                                    @else
                                                        <span class="label label-success">Complete</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-xs btn-info" href=""><span class="fa fa-search"></span></a>
                                                    <a class="btn btn-xs btn-warning" href=""><span class="fa fa-pencil"></span></a>
                                                    <a class="btn btn-xs btn-success" target="_blank" title="Print Letter" href="{{route('release.preview',$payment->id)}}"><span class="fa fa-file-text"></span></a>
                                                    <a class="btn btn-xs btn-info" target="_blank" title="Print Cheque" href="{{route('release.cheque',$payment->id)}}"><span class="fa fa-money"></span></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-file-text-o"></i> निकायसंग सम्बन्धित कागजात/गतिविधिहरु</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice_logs') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice_logs">
                                <table class="table table-bordered table-striped table-hover">
                                @foreach($implementingoffice->activityLogs()->where('type','<>',30)->orderBy('submitted_date','desc')->get() as $activity)
                                        <tr class="papers" data-type="{{ $activity->type }}" data-id="{{$activity->id}}">
                                            <td style="width:15%;vertical-align: middle;text-align:center;">
                                                <strong>{{dateBS($activity->submitted_date)}}</strong>
                                            </td>
                                            <td>
                                                <h4>
                                                    <div class="row">
                                                        <div class="papertitle col-lg-10 col-md-10">
                                                            {{$activity->title}}
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 text-right">
                                                            <a class="deleteActivityLogs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$activity->id}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                                            {!! delete_form(['delete.old.activity_log',$activity->id], 'pro_my_form'.$activity->id) !!}
                                                        </div>
                                                    </div>
                                                </h4>
                                                <hr>
                                                <p>{{$activity->description}}</p>
                                                @foreach($activity->ActivityLogFiles as $file)
                                                    @if(strrchr($file->file_path,'.') == '.pdf')
                                                        <a class="btn btn-danger" target="_blank" title="Click to view file"
                                                           href="{{asset('public/activityFiles/'.$file->file_path)}}">
                                                            <i class="fa fa-file-pdf-o"></i> View PDF File
                                                        </a>
                                                    @else
                                                        <a target="_blank" title="Click to view file"
                                                           href="{{asset('public/activityFiles/'.$file->file_path)}}">
                                                            <img src="{{asset('public/activityFiles/'.$file->file_path)}}"
                                                                 alt="Image" class="img-thumbnail" style="width: 25%">
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home"></span>
                                     Feedback कागजात/गतिविधिहरु</h3>
                                <?php
                                $feedbackPapers=$implementingoffice->activityLogs()->where('type',30);
                                ?>
                                @if($feedbackPapers->count()>0)
                                    {!! minimizeButton('pro_collapse_docs_contractor') !!}
                                @else
                                    <div class="clearfix"></div>
                                @endif
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_docs_contractor">
                                <table class="table table-bordered table-striped table-hover">
                                    @foreach($feedbackPapers->get()->sortByDesc('submitted_date') as $activity)
                                        <tr class="papers" data-type="{{ $activity->type }}" data-id="{{$activity->id}}">
                                            <td style="width:15%;vertical-align: middle;text-align:center;">
                                                <strong>{{dateBS($activity->submitted_date)}}</strong>
                                            </td>
                                            <td>
                                                <h4>
                                                    <div class="row">
                                                        <div class="papertitle col-lg-10 col-md-10">
                                                            {{$activity->title}}
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 text-right">
                                                            <a class="deleteActivityLogs showToolTip confirmButton" href="" title="Move to Trash" data-placement="top" data-form-id="pro_my_form{{$activity->id}}"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                                                            {!! delete_form(['delete.old.activity_log',$activity->id], 'pro_my_form'.$activity->id) !!}
                                                        </div>
                                                    </div>
                                                </h4>
                                                <hr>
                                                <p>{{$activity->description}}</p>
                                                @foreach($activity->ActivityLogFiles as $file)
                                                    @if(strrchr($file->file_path,'.') == '.pdf')
                                                        <a class="btn btn-danger" target="_blank" title="Click to view file"
                                                           href="{{asset('public/activityFiles/'.$file->file_path)}}">
                                                            <i class="fa fa-file-pdf-o"></i> View PDF File
                                                        </a>
                                                    @else
                                                        <a target="_blank" title="Click to view file"
                                                           href="{{asset('public/activityFiles/'.$file->file_path)}}">
                                                            <img src="{{asset('public/activityFiles/'.$file->file_path)}}"
                                                                 alt="Image" class="img-thumbnail" style="width: 25%">
                                                        </a>
                                                    @endif

                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>मातहतका कर्यलायाहरुय</h3>
                                {!! minimizeButton('pro_collapse_implementingoffice_offices_inner') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_implementingoffice_offices_inner">
                                @if($implementingoffice->child()->count()>0)
                                    <ul>
                                    @foreach($implementingoffice->child as $implementingoffice)
                                        <li>
                                            <a
                                                    href="{{route('implementingoffice.show',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                            @if($implementingoffice->child()->count()>0)
                                                <ul>
                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                        <li>
                                                            <a
                                                                    href="{{route('implementingoffice.show',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                            @if($implementingoffice->child()->count()>0)
                                                                <ul>
                                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                                        <li>
                                                                            <a
                                                                                    href="{{route('implementingoffice.show',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-home "></span>
                                    Chief History</h3>
                                {!! minimizeButton('pro_collapse_chief') !!}
                            </div>
                            <div class="panel-body collapse" style="position: relative; top:0; right:0;" id="pro_collapse_chief">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                    </tr>
                                    @forelse($implementingoffice->cheifs()->get()->sortByDesc('id') as $cheif)
                                        <tr>
                                            <td>{{ $cheif->name }}</td>
                                            <td>{{ $cheif->phone }}</td>
                                            <td>{{ $cheif->email }}</td>
                                            <td>{{ $cheif->mobile }}</td>
                                            <td>{{ $cheif->address }}</td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No Chief Recorded yet
                                            </td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight" data-appear-delay="1200">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-file"></i> Activity Log</h3>
                                {!! minimizeButton('pro_collapse_a_log') !!}
                            </div>
                            <div class="panel-body collapse" id="pro_collapse_a_log">
                            {!!Form::open(['route'=>['project.upload.log'],'method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                            <!-- title -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('title','Title:') !!}
                                        {!! Form::text('title', null, ['class'=>'form-control']) !!}
                                        {!! Form::hidden('implementing_office_id', $implementingoffice->id) !!}
                                        @if($errors->has('title'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="titleStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('title') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="titleStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('type','Type:') !!}
                                        <br/>
                                        {!! Form::Select('type',progressActivityLogsTypes()['implementing_office'],null) !!}
                                    </div>
                                </div>
                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('description'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small>
                                                    <i class="fa fa-warning"></i> {!! $errors->first('description') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="descriptionStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- date -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('date','Date:') !!}
                                        <input type="text" class="form-control" name="date" id="date"
                                               value="{{str_replace('/','-',dateBS(date('Y-m-d')))}}">
                                        @if($errors->has('date'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="dateStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('date') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="dateStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- file -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('file')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('file','File:') !!}
                                        {!! Form::file('file[]', ['class'=>'form-control','multiple'=>true]) !!}
                                        @if($errors->has('file'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="fileStatus" class="sr-only">(error)</span>
                                            <div class="alert alert-danger">
                                                <small><i class="fa fa-warning"></i> {!! $errors->first('file') !!}
                                                </small>
                                            </div>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="fileStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                    Upload
                                </button>
                                <button class="btn btn-default" type="reset">Reset</button>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                    {!! Form::close() !!}
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
    <div id="papersEdit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Papers And Files</h4>
                </div>
                <div class="modal-body">
                {!!Form::open(['route'=>['update.old.activity_log'],'method'=>'post','class'=>'showSavingOnSubmit','files'=>true])!!}
                <!-- title -->
                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('title')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group pro_make_full showToolTip">
                                    {!! Form::hidden('paper_id', null,['id'=>'paper_id']) !!}
                                    {!! Form::label('Title','Title:') !!}
                                    {!! Form::text('title', old('title'),['id'=>'paper_title','class'=>"form-control",'placeholder'=>'Title']) !!}
                                    @if($errors->has('title'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                              aria-hidden="true"></span>
                                        <span id="titleStatus" class="sr-only">(error)</span>
                                        <div class="alert alert-danger">
                                            <small>
                                                <i class="fa fa-warning"></i> {!! $errors->first('title') !!}
                                            </small>
                                        </div>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                              aria-hidden="true"></span>
                                        <span id="titleStatus" class="sr-only">(success)</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group pro_make_full showToolTip">
                                    {!! Form::label('dateUpdate','Date:') !!}
                                    {!! Form::text('dateUpdate', old('dateUpdate'),['id'=>'dateUpdate_','class'=>"form-control nepaliDate",'placeholder'=>'Date']) !!}
                                    @if($errors->has('title'))
                                        <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                              aria-hidden="true"></span>
                                        <span id="dateUpdate_Status" class="sr-only">(error)</span>
                                        <div class="alert alert-danger">
                                            <small>
                                                <i class="fa fa-warning"></i> {!! $errors->first('dateUpdate') !!}
                                            </small>
                                        </div>
                                    @elseif(count($errors->all())>0)
                                        <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                              aria-hidden="true"></span>
                                        <span id="dateUpdate" class="sr-only">(success)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-12 col-lg-12 @if($errors->has('liquidated_damage')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                <div class="input-group pro_make_full showToolTip" @if($errors->has('liquidated_damage')) title="{!!$errors->first('type_update')!!}" @endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('type_update','Type:') !!}
                                            {!! Form::Select('type',progressActivityLogsTypes()['implementing_office'],null,['class'=>'paperSelectorEdit']) !!}
                                            @if($errors->has('type_update'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="type_updateStatus" class="sr-only">(error)</span>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="type_updateStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- description -->
                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('remarks')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                        <div class="input-group pro_make_full showToolTip">
                            {!! Form::label('Remarks','कैफियत:') !!}
                            {!! Form::textarea('paperremarks', old('paperremarks'), ['id'=>'paperremarks_update','class'=>'form-control']) !!}
                            @if($errors->has('paperremarks'))
                                <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                      aria-hidden="true"></span>
                                <span id="descriptionStatus" class="sr-only">(error)</span>
                                <div class="alert alert-danger">
                                    <small><i class="fa fa-warning"></i> {!! $errors->first('paperremarks') !!}
                                    </small>
                                </div>
                            @elseif(count($errors->all())>0)
                                <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                      aria-hidden="true"></span>
                                <span id="descriptionStatus" class="sr-only">(success)</span>
                            @endif
                        </div>
                    </div>
                    <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save
                    </button>
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
    <script type="text/javascript" src="{{asset('public/admin/js/editor.js')}}"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $('#district_id').select2();
            $('#date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
        });
    </script>
@stop