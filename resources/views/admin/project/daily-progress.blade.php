@extends('layouts.admin_layout')
@section('headerContent')
    <style>
        .btn-default.active.present {
            background: #78e078 !important;
            color: white;
        }

        .btn-default.active.absent {
            background: #f57e7e !important;
            color: white;
        }

    </style>
@stop

@section('content')
    @php
    $setting = $project
        ->projectSettings()
        ->where('fy', session()->get('pro_fiscal_year'))
        ->first();
    if (!$setting) {
        $setting = $project;
    }
    @endphp
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a>
        </li>
        <li><a href="{{ route('project.index') }}"><i class="fa fa-buysellads"></i> Projects</a></li>
        <li class="active"><a href="{{ route('project.show', $project->id) }}"><i class="fa fa-edit"></i>
                {{ $project->name }}</a></li>
        <li class="active">
            <a href="{{ route('daily.progress', $project->id) }}"><i class="fa fa-plus"></i> Add Daily Progress</a>
        </li>
        <li class="active">
            <a href="{{ route('report.daily.progress', $project->id) }}"><i class="fa fa-eye"></i> View Daily
                Progress</a>
        </li>
    </ol>

    <div class="container-fluid">
        <h1 style="margin-top:5px">{{ $project->name }} - {{ $setting->project_code }}
            ({{ $setting->implementing_office->name }})
            <i class="h4 text-danger">[Last Updated At:
                {{ isset($daily_progress) ? dateBS(date('Y-m-d', strtotime($daily_progress->updated_at))) : '' }}]</i>

            <span style="float: right; margin-right: 200px;">
                @if (!$editable_daily_progress->isEmpty())
                    <i class="h4 text-danger" style="font-weight: bold ">Edit DailyProgress of </i>
                    @foreach ($editable_daily_progress as $progress)
                        <a href="{{ route('daily.progress.edit', $progress->id) }}"
                            title="Edit DailyProgress of {{ \Carbon\Carbon::now()->format('Y-m-d') == $project->date ? 'Today' : $progress->date }}">

                            @if (isset($daily_progress))
                                @if ($daily_progress->date == $progress->date)
                                    <i class="h4 text-danger" style="text-decoration: underline;"> <strong>
                                            {!! $progress->date !!} </strong></i>
                                @else
                                    <i class="h4 text-danger" style="text-decoration: underline;"> {!! $progress->date !!}
                                    </i>
                                @endif
                            @else
                                <i class="h4 text-danger" style="text-decoration: underline;"> {!! $progress->date !!} </i>
                            @endif




                        </a>
                    @endforeach
                @endif
            </span>

        </h1>
        <p>{!! nl2br($project->description) !!}</p>
        <br>
        <div class="clearfix"></div>

        {!! Form::open(['route' => ['daily.progress.store', $project->id], 'method' => 'post', 'class' => 'showSavingOnSubmit form-horizontal', 'files' => true]) !!}
        {{-- Date --}}
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-lg-2 col-md-2 pull-right">
                <label for="date"> Date of this Daily Progress </label>
                <input name="date" type="text" class="form-control nepaliDate" value="{{ dateBS(date('Y-m-d')) }}"
                    id="date" />
            </div>
        </div>

        {{-- ----------Manpower at site-------------- --}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-user"></i>
                    Manpower at site</h3>
                {!! minimizeButton('pro_collapse_manpower') !!}
            </div>
            <div class="panel-body collapse {{ app()->environment() == 'local' ? 'in' : '' }}"
                id="pro_collapse_manpower">
                @foreach (manpowerTypes() as $manpower)
                    @foreach ($project->engineers as $engineer)
                        <h4 class="bg-info text-white" style="padding:10px">{{ $manpower }}</h4>
                        {{-- <div class="form-group">
                            <label for="manpower" class="col-sm-2 control-label">{{ $manpower.'  manpower title' }}</label>
                            <div class="col-sm-6">
                                {!! Form::select('manpower['.$manpower.'][project_'.$manpower.'human_resource_title][id][]', ['id'=>'project_'.$manpower.'_human_resources'], ['selected_project_human_resources'], ['class'=>'form-control','multiple','onchange'=>'attendence(this, "project_human_resource","'.$manpower.'")']) !!}
                            </div>
                        
                        </div> --}}

                        <?php
                        $selectedUsers = $project->DailyProgressUsers()->get();
                        $selectedUsers = $selectedUsers->pluck('id')->toArray();
                        ?>

                        @php
                            $currentManpower = $project->dailyprogress->first()->manpower;
                            $projectChief = $currentManpower['Client']['Project Chief']['id'][0];
                            //dd($projectChief);
                        @endphp
                        @if ($manpower === 'Consultant')
                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('lead', 'Team Leader') !!}
                                {!! Form::select('project->engineers[]', $dailyProgressUsers, $projectChief, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>


                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('arch', 'Architect') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $engineer->id, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>

                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('lead', 'Structure Engineer') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $selectedUsers, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>

                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('lead', 'Project manager') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $engineer->id, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>

                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('consultant', 'Consultant') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $selectedUsers, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>
                        @elseif($manpower === 'Client')
                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('chief', 'Project Chief') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $selectedUsers, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>

                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('lead', 'Deputy Project Chief') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $selectedUsers, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>


                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('lead', 'Engineer') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $selectedUsers, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>

                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('lead', 'Supervisor') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $selectedUsers, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>
                        @elseif($manpower == 'Contractor')
                            <div class="input-group pro_make_full showToolTip">
                                {!! Form::label('lead', 'Senior lab technician') !!}
                                {!! Form::select('users[]', $dailyProgressUsers, $selectedUsers, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                @if ($errors->has('manpower'))
                                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(error)</span>
                                    <div class="alert alert-danger">
                                        <small><i class="fa fa-warning"></i> {!! $errors->first('manpower') !!}
                                        </small>
                                    </div>
                                @elseif(count($errors->all()) > 0)
                                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                        aria-hidden="true"></span>
                                    <span id="manpowerStatus" class="sr-only">(success)</span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endforeach

                <hr>
                <div class="panel-footer">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>
        {{-- ----------End Manpower at site-------------- --}}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-wrench"></i>
                    Tools and Equipment at site</h3>
                {!! minimizeButton('pro_collapse_equipment') !!}
            </div>
            <div class="panel-body collapse {{ app()->environment() == 'local' ? 'in' : '' }}"
                id="pro_collapse_equipment">
                @foreach ($equipments as $equipment)
                    <div class="form-group">
                        <label for="equipments" class="col-sm-3 control-label">{{ $equipment->title }}</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        {!! Form::input('number', 'equipments[' . $equipment->title . '][quantity]', $daily_progress->equipments[$equipment->title]['quantity'] ?? null, ['class' => 'form-control']) !!}
                                        <input type="hidden" name="equipments[{{ $equipment->title }}][unit]"
                                            value="{{ $equipment->unit }}">
                                        <span class="input-group-addon">{{ ucfirst($equipment->unit) }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        {!! Form::select('equipments[' . $equipment->title . '][status]', ['Select Status', 'Working', 'Idle'], $daily_progress->equipments[$equipment->title]['status'] ?? null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                @endforeach
                <div class="panel-footer">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-wrench"></i>
                    Materials stock day ends</h3>
                {!! minimizeButton('pro_collapse_material') !!}
            </div>
            <div class="panel-body collapse {{ app()->environment() == 'local' ? 'in' : '' }}"
                id="pro_collapse_material">
                @foreach ($materials as $material)
                    <div class="form-group">
                        <label for="materials" class="col-sm-3 control-label">{{ $material->title }}</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                {!! Form::input('number', 'materials[' . $material->title . '][quantity]', $daily_progress->materials[$material->title]['quantity'] ?? null, ['class' => 'form-control']) !!}
                                <input type="hidden" name="materials[{{ $material->title }}][unit]"
                                    value="{{ $material->unit }}">
                                <span class="input-group-addon">{{ ucfirst($material->unit) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="panel-footer">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-bolt"></i>
                    Weather Condition (Sunny/Cloudy/Rainning)</h3>
                {!! minimizeButton('pro_collapse_weather') !!}
            </div>
            <div class="panel-body collapse {{ app()->environment() == 'local' ? 'in' : '' }}"
                id="pro_collapse_weather">
                <div class="form-group">
                    <label for="weather" class="col-sm-3 control-label">Weather Condition</label>
                    <div class="col-sm-3">
                        {!! Form::select('weather', $weathers, $daily_progress->weather ?? null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="weather_remarks" class="col-sm-3 control-label">Remarks</label>
                    <div class="col-sm-3">
                        {!! Form::text('weather_remarks', $daily_progress->weather_remarks ?? null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-cloud"></i>
                    Temperature ( &deg;C )</h3>
                {!! minimizeButton('pro_collapse_temperature') !!}
            </div>
            <div class="panel-body collapse {{ app()->environment() == 'local' ? 'in' : '' }}"
                id="pro_collapse_temperature">
                <div class="form-group">
                    <label for="max" class="col-sm-2 control-label">Max</label>
                    <div class="col-sm-3">
                        {!! Form::input('number', 'temperature[max]', $daily_progress->temperature['max'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <label for="min" class="col-sm-2 control-label">Min</label>
                    <div class="col-sm-3">
                        {!! Form::input('number', 'temperature[min]', $daily_progress->temperature['min'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-crosshairs"></i>
                    Problem Encountered / Site Issues</h3>
                {!! minimizeButton('pro_collapse_issues') !!}
            </div>
            <div class="panel-body collapse {{ app()->environment() == 'local' ? 'in' : '' }}" id="pro_collapse_issues">
                <div class="form-group">
                    <label for="problems" class="col-sm-2 control-label">Issues</label>
                    <div class="col-sm-10">
                        @if (isset($daily_progress))
                            @foreach (array_filter($daily_progress->problems) as $problem)
                                {!! Form::textarea('problems[]', $problem, ['class' => 'form-control', 'rows' => '2']) !!}
                                <br>
                            @endforeach
                        @endif
                        {!! Form::textarea('problems[]', null, ['class' => 'form-control', 'rows' => '2']) !!}
                    </div>
                    <div class="clearfix"></div>
                    <br>
                </div>
                <button type="button" class="btn btn-sm btn-info pull-right" onclick="addMoreIssues()"><i
                        class="fa fa-plus"></i> Add More
                </button>
                <div class="clearfix"></div>
                <br>
                <div class="panel-footer">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>

        {{-- Quantity Work Done --}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-cubes"></i>
                    Quantity Work done</h3>
                {!! minimizeButton('pro_collapse_activity') !!}
            </div>
            <div class="panel-body collapse  {{ app()->environment() == 'local' ? 'in' : '' }}"
                id="pro_collapse_activity">
                <div class="col-sm-1">
                    Code :
                </div>
                <div class="col-sm-4">
                    <select name="" id="work_activity_code">
                        <option value="placeholder">Select Activity</option>
                        @foreach ($activities as $activity)
                            <option value="{{ $activity->id }}" data-activity-title="{{ $activity->title }}"
                                code="{{ $activity->code }}" data-activity-rate="{{ $activity->unit }}">
                                {{ $activity->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-info" disabled id="activity_add_button" type="button"
                        onclick="add_work_activity(this)">Add Activity
                    </button>
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="row" style="text-align: left;">
                    <p class="col-sm-1" style="width:3%"><strong>S.n</strong></p>
                    <p class="col-sm-1"><strong>Code</strong></p>
                    <p class="col-sm-2"><strong>Description</strong></p>
                    <p class="col-sm-1"><strong>Unit</strong></p>
                    <p class="col-sm-2"><strong>Block</strong></p>
                    <p class="col-sm-1"><strong>Q</strong></p>
                    <p class="col-sm-1"><strong>Progress</strong></p>
                    <p class="col-sm-1"><strong>Remarks</strong></p>
                    <p class="col-sm-1"></p>
                </div>
                <div class="clearfix"></div>
                <div style="border-bottom: 1px solid #ddd;margin :5px 0"></div>
                <div id="activities_block">
                    @if (isset($daily_progress->activities) && $daily_progress->activities && count($daily_progress->activities))
                        @foreach ($daily_progress->activities as $activity_index => $activity)
                            <?php
                            $activity_master = \PMIS\WorkActivity::find($activity['id']);
                            $activity_block = array_key_exists('block', $activity) ? \PMIS\ProjectBlocks::find($activity['block']) : null;
                            ?>
                            <div class="row">
                                <input type="hidden" name="activities[id][]" value="{{ $activity['id'] ?? null }}">
                                <div class="col-sm-1" style="width:3%"><strong>{{ $activity_index + 1 }}</strong>
                                </div>
                                <div class="col-sm-1">{{ optional($activity_master)->code }}</div>
                                <div class="col-sm-2">{{ optional($activity_master)->title }}</div>
                                <div class="col-sm-1">{{ optional($activity_master)->unit }}</div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="activities[block][]">
                                        @foreach ($blocks as $id => $block_name)
                                            <option value="{{ $id }}"
                                                {{ $activity_block ? ($activity_block->id == $id ? 'selected' : '') : '' }}>
                                                {{ $block_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-1"><input type="number" name="activities[q][]"
                                        class="form-control" min="1" value="{{ $activity['q'] ?? null }}"></div>
                                <div class="col-sm-1"><input type="number" name="activities[progress][]"
                                        class="form-control" value="{{ $activity['progress'] ?? null }}">

                                </div>
                                <div class="col-sm-1"><input type="text" name="activities[remarks][]"
                                        class="form-control"
                                        value="{{ array_key_exists('remarks', $activity) ? $activity['remarks'] : null }}">
                                </div>
                                <div class="col-sm-1">
                                    <button class="btn btn-danger" type="button" onClick="removeActivity(this)"><i
                                            class="fa fa-times"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div><br>
                        @endforeach
                    @endif
                </div>
                <div class="clearfix"></div>
                <br><br><br>
                <div class="panel-footer">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>

        {{-- Images/Videos --}}
        <div class="panel panel-default pro_news_detail">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-camera"></span> Images/Video</h3>
                <button type="button" class="btn btn-xs btn-danger pull-right showToolTip" onclick="removeImages()"
                    title="Remove All Images/Videos"><span class="glyphicon glyphicon-minus"></span></button>
                <button type="button" class="btn btn-xs btn-warning pull-right showToolTip" onclick="addImages()"
                    title="Add New Image/Video"><span class="glyphicon glyphicon-plus"></span></button>
                {!! minimizeButton('pro_collapse_a_log') !!}
            </div>

            <br>

            <div class="panel-body pro_images_browser collapse {{ app()->environment() == 'local' ? 'in' : '' }}"
                id="pro_collapse_a_log">
                <?php $i = 1; ?>
                @foreach ($existing_logs as $activityLog)
                    @foreach ($activityLog->ActivityLogFiles as $media)
                        <div class="col-md-2 col-lg-2">
                            @php
                                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
                                $videoExtensions = ['mp4', 'ogg', 'webm'];
                                $explodeImage = explode('.', $media->file_path);
                                $extension = end($explodeImage);
                            @endphp
                            @if (in_array($extension, $imageExtensions))
                                <img src="{{ asset('public/activityFiles/' . $media->file_path) }}" width="300"
                                    alt="{{ $media->name }}" title="{{ $media->title }}"
                                    class="img img-responsive" /><br><br>
                            @elseif(in_array($extension, $videoExtensions))
                                <video width="280" height="200" controls>
                                    <source src="{{ asset('public/activityFiles/' . $media->file_path) }}"
                                        type="video/mp4">
                                    <source src="{{ asset('public/activityFiles/' . $media->file_path) }}"
                                        type="video/ogg">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                            <p><strong>Type: </strong>{{ $media->type }}</p>
                            <p><strong>Description: </strong>{{ $media->description }}</p>
                        </div>
                    @endforeach
                @endforeach

                <div class="col-md-2 col-lg-2 pro_images_section">
                    <span class="glyphicon glyphicon-remove-circle closeButton" onclick="hideDiv($(this))"></span>
                    <label for="file{{ $i }}"><span class="glyphicon glyphicon-plus"></span> Add a
                        Image/Video</label>
                    <input type="file" name="file[]" class="form-control col-md-3 col-lg-3" id="file{{ $i }}"
                        onchange="readURL(this)" multiple />
                    <select name="type[]" id="" data-placeholder="Add Type..." data-tags="true" class="select2tags">
                        <option value="">&nbsp;</option>
                        @foreach ($existing_files as $file)
                            @if ($file->type)
                                <option value="{{ $file->type }}">{{ $file->type }}</option>
                            @endif
                        @endforeach
                    </select><br><br>
                    {{-- <input type="text" name="type[]" class="form-control col-md-3 col-lg-3" placeholder="Add Type..." /> --}}
                    <input type="text" name="description[]" class="form-control col-md-3 col-lg-3"
                        placeholder="Add Description" />
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="panel-footer">
                <button class="btn btn-sm btn-success" type="submit">Update</button>
            </div>
        </div>


        <div class="div-stock-existing-files">
            <select name="type[]" id="" data-placeholder="Add Type..." data-tags="true"
                class="selectize select-stock-existing-files">
                <option value="">&nbsp;</option>
                @foreach ($existing_files as $file)
                    @if ($file->type)
                        <option value="{{ $file->type }}">{{ $file->type }}</option>
                    @endif
                @endforeach
            </select>
            {!! Form::close() !!}
        </div>
    </div>
@stop
@section('footerContent')

    {{-- <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 1 // Options | Number of years to show
            });
        });
        $('document').ready(function() {
            $(".pro_images_browser").sortable({
                tolerance: 'pointer',
                revert: 'invalid',
                placeholder: '',
                forceHelperSize: true
            });
        });

        var $i = {{ $i + 1 }};

        function addImages() {
            div_stock_existing_files = $('.div-stock-existing-files').clone();
            select_stock_existing_files = $('.select-stock-existing-files').clone();
            select_stock_existing_files.select2();
            $('.pro_images_section:last-child').after(
                '<div class="col-md-2 col-lg-2 pro_images_section"> <span class="glyphicon glyphicon-remove-circle closeButton" onclick="hideDiv($(this))"></span> <label for="file' +
                $i +
                '"><span class="glyphicon glyphicon-plus"></span> Add a Image</label> <input type="file" name="file[]" class="form-control col-md-3 col-lg-3" id="file' +
                $i + '" onchange="readURL(this)" /> ' + div_stock_existing_files.html() +
                '<br><br><input type="text" name="description[]" class="form-control col-md-3 col-lg-3 file-desc" placeholder="Add Description" /><input type="hidden" name="oldImages[]" value="0" /> <div class="clearfix"></div></div>'
            );
            $('.pro_images_section:last-child').find('select').each(function() {
                $(this).select2();
            });
            $i++;
        }
    </script>
    <script>
        function attendence($this, title, associated_with) {
            if ($($this).find('option').not(':selected').length > 0) {
                var notSelected = $($this).find('option').not(':selected');
                notSelected.map(function() {
                    $('.attendance > div#' + this.value + 'attendance').remove()
                });
            }

            if ($($this).find('option:selected').length > 0) {
                var selected = $($this).find('option:selected');
                selected.map(function() {
                    var id = this.value + 'attendance';
                    if (!$('#' + id).length) {
                        var person = $(this).html()
                        var checkbox =
                            '<div class="col-sm-1 btn-group btn-group-toggle" data-toggle="buttons" id="' + id +
                            '" >' +
                            person.substring(0, 15) + '...<br>' +
                            '<label class="btn btn-default active present">' +
                            '<input type="radio" name="manpower[' + associated_with + '][' + title +
                            '][attendence][' + this.value + ']" autocomplete="off" checked value="1" multiple> P' +
                            '</label>' +
                            '<label class="btn btn-default absent">' +
                            '<input type="radio" name="manpower[' + associated_with + '][' + title +
                            '][attendence][' + this.value + ']" autocomplete="off" value="0" multiple> A' +
                            '</label>' +
                            '</div>'
                        $($($this).closest('.form-group').find('.attendance')).append(checkbox);
                    }
                });
            }
        }

        function replaceSpecialCharsSpaces(str) {
            return str.replace(/[^a-z0-9\s]/gi, '_').replace(/[_\s]/g, '_')
        }

        $('#work_activity_code').on('change', function() {
            var selected_value = $(this).find('option:selected').val()
            if (selected_value != 'placeholder') {
                $('#activity_add_button').removeAttr('disabled');
            } else {
                $('#activity_add_button').attr('disabled', 'disabled');
            }
        })

        $('#material-choose').on('change', function() {
            var selected_value = $(this).find('option:selected').val()
            if (selected_value != 'placeholder') {
                $('#sample_add_button').removeAttr('disabled');
            } else {
                $('#sample_add_button').attr('disabled', 'disabled');
            }
        })

        function add_work_activity($this, description, rate) {
            var selected_value = $('#work_activity_code').find('option:selected')
            var value = $(selected_value).val()
            var id = value + '_activity_desc'
            // if(!$('#'+id).length){
            var code = $(selected_value).attr('code')
            var description = $(selected_value).attr('data-activity-title')
            var rate = $(selected_value).attr('data-activity-rate')
            var serial_no = ($('#activities_block').find('.row').length) + 1
            var html = '<div class="row">' +
                '                                <input type="hidden" name="activities[id][]" value="' + value + '">' +
                '                                <div class="col-sm-1" style="width:3%"><strong>' + serial_no +
                '</strong></div>' +
                '                                <div class="col-sm-1">' + code + '</div>' +
                '                                <div class="col-sm-2">' + description + '</div>' +
                '                                <div class="col-sm-1">' + rate + '</div>' +
                '                                <div class="col-sm-2"><select class="form-control" name="activities[block][]"></select></div>' +
                '                                <div class="col-sm-1"><input type="number" name="activities[q][]" class="form-control" min="1"></div>' +
                '                                <div class="col-sm-1"><input type="number" name="activities[progress][]" class="form-control" ></div>' +
                '                                <div class="col-sm-1"><input type="text" name="activities[remarks][]" class="form-control" ></div>' +
                '                                <div class="col-sm-1"> <button class="btn btn-danger" type="button" onClick="removeActivity(this)"><i class="fa fa-times"></i></button> </div>' +
                '                                <div class="clearfix"></div>' +
                '        </div><br>'

            $('#activities_block').append(html)

            $('#activities_block').find('select').select2();
            var blocks = <?php echo json_encode($blocks); ?>; //blocks from controller
            $.each(blocks, function(key, value) {
                $('#activities_block').find('select').append("<option value='" + key + "'>" + value + "</option>")
            });

            $('.nepaliDate').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
            // $('#sample_block').find('.nepaliDate').each(function () {
        }

        function removeActivity($this) {
            $($this).closest('div.row').remove();
        }

        function removeSample($this) {
            $($this).closest('div.row').remove();
        }

        function addMoreIssues() {
            var input = '<label for="problems" class="col-sm-2 control-label">&nbsp;</label>' +
                '<div class="col-sm-10">' +
                '<textarea name="problems[]" class="form-control" rows=2></textarea>' +
                '</div><div class="clearfix"></div><br>'
            $('#pro_collapse_issues .form-group').append(input);
        }

        /*media js*/
        function hideDiv($this) {
            var $selector = $this.parent('div.pro_images_section');
            var $val = $selector.find('input[data-pro-name=useMeDelete]').val();
            $selector.find('input[data-pro-name=deleteMe]').val($val);
            $selector.fadeOut('slow');
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader, img, $label;
                for (var i = 0; i < input.files.length; i++) {

                    reader = new FileReader();
                    reader.onload = function(e) {
                        img = $('<img >'); //Equivalent: $(document.createElement('img'))
                        img.attr('src', e.target.result);
                        $label = $('.pro_images_browser .pro_images_section:last-child label');
                        $label.css('border', 'none');
                        $label.html('');
                        img.appendTo($label);
                        addImages();
                    };
                    reader.readAsDataURL(input.files[i]);
                }

            }
        }

        function removeImages() {
            var $selector = $('.pro_images_section');
            $selector.each(function() {
                var $val = $(this).find('input[data-pro-name=useMeDelete]').val();
                $(this).find('input[data-pro-name=deleteMe]').val($val);
                $(this).fadeOut('slow');
            });
        }
    </script>
@stop
