<!-- Project Managers -->
<?php $selectedProjectManagers = $project->Engineers()->where('type', 'Project Manager')->get();
$selectedProjectManagers = $selectedProjectManagers->pluck('id')->toArray();
?>
<div class="form-group col-md-12 col-lg-12 @if($errors->has('project_managers')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
    <div class="input-group pro_make_full showToolTip">
        {!! Form::label('project_managers','ProjectManagers') !!}
        {!! Form::select('project_managers[]', $project_managers ,$selectedProjectManagers, ['class'=>'form-control','multiple'=>'multiple']) !!}
        @if($errors->has('project_managers'))
            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="project_managersStatus" class="sr-only">(error)</span>
            <div class="alert alert-danger">
                <small><i class="fa fa-warning"></i> {!! $errors->first('project_managers') !!}
                </small>
            </div>
        @elseif(count($errors->all())>0)
            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="project_managersStatus" class="sr-only">(success)</span>
        @endif
    </div>
</div>




<!-- contractor_project_managers -->
<div class="form-group col-md-12 col-lg-12 @if($errors->has('contractor_project_managers')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
    <div class="input-group pro_make_full showToolTip">
        {!! Form::label('contractor_project_managers','Project Manager') !!}
        {!! Form::select('contractor_project_managers[]',$contractor_p_managers, $p_managers, ['multiple'=>'multiple','class'=>'form-control']) !!}
        @if($errors->has('contractor_project_managers'))
            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_project_managersStatus" class="sr-only">(error)</span>
            <div class="alert alert-danger">
                <small>
                    <i class="fa fa-warning"></i> {!! $errors->first('contractor_project_managers') !!}
                </small>
            </div>
        @elseif(count($errors->all())>0)
            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_project_managersStatus" class="sr-only">(success)</span>
        @endif
    </div>
</div>

<div class="form-group col-md-12 col-lg-12 @if($errors->has('contractor_engineers')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
    <div class="input-group pro_make_full showToolTip">
        {!! Form::label('contractor_engineers','Engineers') !!}
        {!! Form::select('contractor_engineers[]',$contractor_engineers, $c_engineers, ['multiple'=>'multiple','class'=>'form-control']) !!}
        @if($errors->has('contractor_engineers'))
            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_engineersStatus" class="sr-only">(error)</span>
            <div class="alert alert-danger">
                <small>
                    <i class="fa fa-warning"></i> {!! $errors->first('contractor_engineers') !!}
                </small>
            </div>
        @elseif(count($errors->all())>0)
            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_engineersStatus" class="sr-only">(success)</span>
        @endif
    </div>
</div>

<div class="form-group col-md-12 col-lg-12 @if($errors->has('contractor_saftey_officers')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
    <div class="input-group pro_make_full showToolTip">
        {!! Form::label('contractor_saftey_officers','Saftey Officers') !!}
        {!! Form::select('contractor_saftey_officers[]',$contractor_saftey_officers, $c_s_officer, ['multiple'=>'multiple','class'=>'form-control']) !!}
        @if($errors->has('contractor_saftey_officers'))
            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_saftey_officersStatus" class="sr-only">(error)</span>
            <div class="alert alert-danger">
                <small>
                    <i class="fa fa-warning"></i> {!! $errors->first('contractor_saftey_officers') !!}
                </small>
            </div>
        @elseif(count($errors->all())>0)
            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_saftey_officersStatus" class="sr-only">(success)</span>
        @endif
    </div>
</div>

<div class="form-group col-md-12 col-lg-12 @if($errors->has('contractor_skill_manpowers')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
    <div class="input-group pro_make_full showToolTip">
        {!! Form::label('contractor_skill_manpowers','Skill Manpower') !!}
        {!! Form::select('contractor_skill_manpowers[]',$contractor_skill_manpower, $c_skill, ['multiple'=>'multiple','class'=>'form-control']) !!}
        @if($errors->has('contractor_skill_manpowers'))
            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_skill_manpowersStatus" class="sr-only">(error)</span>
            <div class="alert alert-danger">
                <small>
                    <i class="fa fa-warning"></i> {!! $errors->first('contractor_skill_manpowers') !!}
                </small>
            </div>
        @elseif(count($errors->all())>0)
            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_skill_manpowersStatus" class="sr-only">(success)</span>
        @endif
    </div>
</div>

<div class="form-group col-md-12 col-lg-12 @if($errors->has('contractor_unskill_manpowers')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
    <div class="input-group pro_make_full showToolTip">
        {!! Form::label('contractor_unskill_manpowers','Unskill Manpower') !!}
        {!! Form::select('contractor_unskill_manpowers[]',$contractor_unskill_manpower, $c_unskill, ['multiple'=>'multiple','class'=>'form-control']) !!}
        @if($errors->has('contractor_unskill_manpowers'))
            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_unskill_manpowersStatus" class="sr-only">(error)</span>
            <div class="alert alert-danger">
                <small>
                    <i class="fa fa-warning"></i> {!! $errors->first('contractor_unskill_manpowers') !!}
                </small>
            </div>
        @elseif(count($errors->all())>0)
            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                  aria-hidden="true"></span>
            <span id="contractor_unskill_manpowersStatus" class="sr-only">(success)</span>
        @endif
    </div>
</div>


<!--Consultant Team-->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title pull-left"><i class="fa fa-file"></i> Consultant Team</h3>
        {!! minimizeButton('pro_project_consultant') !!}
    </div>
{!!Form::open(['route'=>['project.engineers.add', $project->id],'method'=>'POST','class'=>'showSavingOnSubmit'])!!}

<!-- Types -->
    @foreach(consultantMemberTypes() as $type)
        <?php
        $selected = $project->Engineers()->where('type', $type)->get();
        $selected = $selected->pluck('id')->toArray();
        $id = str_replace($type);
        dd($id);
        ?>
        <div class="form-group col-md-12 col-lg-12 @if($errors->has($id)) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
            <div class="input-group pro_make_full showToolTip">
                {!! Form::label($id, 'Team Leaders') !!}
                {!! Form::select($id.'[]', $type ,$selected, ['class'=>'form-control','multiple'=>'multiple']) !!}
                @if($errors->has($id))
                    <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                          aria-hidden="true"></span>
                    <span id="{{$id}}Status" class="sr-only">(error)</span>
                    <div class="alert alert-danger">
                        <small><i class="fa fa-warning"></i> {!! $errors->first($id) !!}
                        </small>
                    </div>
                @elseif(count($errors->all())>0)
                    <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                          aria-hidden="true"></span>
                    <span id="{{$id}}Status" class="sr-only">(success)</span>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{--<div class="panel panel-default">--}}

{{--<div class="panel-footer">--}}
{{--<button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">Save--}}
{{--</button>--}}
{{--<button class="btn btn-default" type="reset">Reset</button>--}}
{{--</div>--}}
{{--</div>--}}
{!! Form::close() !!}



{{-- daily-progress-report--}}

@if(isset($progresses) && $progresses)
    <div class="detail" id="manpower_at_site">
        <button class="btn btn-success" onclick="exportToExcel2('#manpower_at_site')">Export</button>
        <div class="text-center"><h3>Manpower at Site</h3></div>
        <table class="table table-bordered" id="main">
            <thead>
            
            <th>S.N</th>
            <th>Description</th>
            <th>Unit/Name</th>
            <th>Quantity/Attendence</th>
            <th>Manpower Type</th>
            <th>Date</th>
            </thead>
            <tbody>
            <?php $equipment_index = $manpower_index = $material_index = 1;?>
            
            @foreach($progresses as $progress)
                @if($progress->manpower !== null)
                    @foreach($progress->manpower as $manpower_type_title => $manpower_type)
                        @foreach($manpower_type as $manpower_title => $manpower)
                            <tr>
                                <td>{{ $manpower_index }}</td>
                                <td>{{ $manpower_title }}</td>
                                <td>
                                
                                @if(isset($manpower['unit']))
                                    {{$manpower['unit']}}
                                @elseif(isset($manpower['id']))
                                    <!--                                                        --><?php //$attendence = [];?>
                                    @foreach($manpower['id'] as $member_index => $id)
                                        @if($manpower_type_title == 'Client')
                                            <!--                                                                --><?php //$member = PMIS\Engineer::find($id);?>
                                        @elseif($manpower_type_title == 'Consultant')
                                            <!--                                                                --><?php //$member = PMIS\AuthorizedPerson::find($id);?>
                                        @elseif($manpower_type_title == 'Contractor')
                                            <!--                                                                --><?php //$member = PMIS\AuthorizedPerson::find($id);?>
                                        @endif
                                        ({{$member_index+1}}) {{ optional($member)->name }}
                                        <!--                                                            --><?php //$attendence[] = $manpower['attendence'][$id] == 1 ? 'Present' : 'Absent'; ?>
                                        @endforeach
                                    
                                    @else
                                        @if(is_array($manpower))
                                            @foreach($manpower as $sm => $mt)
                                                <p class="small">{{ $sm }}/{{ $mt['unit'] }}</p>
                                            @endforeach
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if(isset($manpower['quantity']))
                                        {{$manpower['quantity']}}
                                    @elseif(isset($manpower['id']) && $attendence)
                                        {{ json_encode($attendence) }}
                                    @else
                                        @if(is_array($manpower))
                                            @foreach($manpower as $sm => $mt)
                                                <p>{{ $mt['quantity'] }}</p>
                                            @endforeach
                                        @endif
                                    @endif
                                </td>
                                <td style="font-size:large">
                                    <strong>{{ $manpower_type_title}}</strong></td>
                                <td>{{$progress->date}}</td>
                            </tr>
                            <?php $manpower_index++; ?>
                        @endforeach
                    @endforeach
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

@endif
@if(isset($progresses) && $progresses)
    <div class="detail" id="materials_stock">
        <button class="btn btn-success" onclick="exportToExcel2('#materials_stock')">Export</button>
        
        <div class="text-center"><h3>Materials stock day ends	</h3></div>
        <table class="table table-bordered" id="main">
            <thead>
            
            <th>S.N</th>
            <th>Description</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th>Date</th>
            </thead>
            <tbody>
            
            @foreach($progresses as $progress)
                @if($progress->materials !== null)
                    @foreach($progress->materials as $material_title => $material)
                        @if ($material['quantity'])
                            <tr>
                                <td>{{ $material_index }}</td>
                                <td>{{ $material_title }}</td>
                                <td>{{ $material['unit']??'' }}</td>
                                <td>{{ $material['quantity']??'' }}</td>
                                <td>{{ $progress->date }}</td>
                            </tr>
                            <!--                                                --><?php //$material_index++; ?>
                        @endif
                    @endforeach
                @endif                                @endforeach
            </tbody>
        </table>
    </div>

@endif
@if(isset($progresses) && $progresses)
    <div class="detail" id="tools_and_equipments">
        <button class="btn btn-success" onclick="exportToExcel2('#tools_and_equipments')">Export</button>
        
        <div class="text-center"><h3>Tools and Equipments at site	</h3></div>
        <table class="table table-bordered" id="main">
            <thead>
            
            <th>S.N</th>
            <th>Description</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Date</th>
            </thead>
            <tbody>
            @foreach($progresses as $progress)
                @if($progress->equipments !== null)
                    @foreach($progress->equipments as $equipment_title => $equipment)
                        @if ($equipment['quantity'])
                            <tr>
                                <td>{{ $equipment_index }}</td>
                                <td>{{ $equipment_title }}</td>
                                <td>{{ $equipment['unit']??'' }}</td>
                                <td>{{ $equipment['quantity']??'' }}</td>
                                @php
                                    $status = $equipment['status'] ?? null;
                                    if($status !== null){
                                        if($status == 0){
                                            $status = "-";
                                        }elseif($status == 1){
                                            $status = "Working";
                                        }elseif ($status == 2){
                                            $status = "Idle";
                                        }
                                    }
                                @endphp
                                <td>{{ $status }}</td>
                                <td>{{ $progress->date }}</td>
                            </tr>
                            <!--                                            --><?php //$equipment_index++; ?>
                        @endif
                    @endforeach
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

@endif














{{-- daily-progress-backup 433--}}

<!--<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th><strong>S.n</strong></th>
                        <th><strong>Block</strong></th>
                        <th><strong>Sample Code</strong></th>
                        <th><strong>Material Detail</strong></th>
                        <th><strong>Unit</strong></th>
                        <th><strong>Quantity</strong></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="sample_block">
                    @if(isset($daily_progress->samples) && $daily_progress->samples && count($daily_progress->samples))
    @foreach($daily_progress->samples as $sample_index => $sample)
        <?php

        $sample_master = PMIS\Material::find($sample['id']);

        ?>
                <tr>
                    <td>
                        <input type="hidden" name="samples[id][]" value="{{ $sample['id']??null }}">
                                    <strong>{{ $sample_index+1 }}</strong>
                                </td>
                                <td>
                                    <input type="text" name="samples[block][]" class="form-control" value="{{ $sample['block']??null }}">
                                </td>
                                <td>
                                    <strong>{{ optional($sample_master)->title }}</strong>
                                </td>
                                <td>
                                    <input type="text" name="samples[sample_code][]" class="form-control"
                                           value="{{ $sample['sample_code']??null }}">
                                </td>
                                <td>
                                    <input type="text" name="samples[unit][]" class="form-control"
                                           value="{{ $sample['unit']??null }}">
                                </td>
                                <td>
                                    <input type="text" name="samples[quantity][]" class="form-control"
                                           value="{{ $sample['quantity']??null }}">
                                </td>
                                <td>
                                    <button class="btn btn-danger" type="button" onClick="removeActivity(this)"><i
                                                class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        @endforeach
@endif
        </tbody>
    </table>-->



DailyProgressReport Section 2 BackupManpower
--------------------------------------------------------------------------------------------
<td width="20%">
    @if(array_key_exists('unit', $manpower))
        @if(isset($manpower['unit']))
            {{$manpower['unit']}}
        @endif
    @elseif(array_key_exists('unit', $manpower))
        @if(isset($manpower['id']))
            <?php $attendence = [];?>
            @foreach($manpower['id'] as $member_index => $id)
                @if($manpower_type_title == 'Client')
                    <?php $member = PMIS\Engineer::find($id);?>
                @elseif($manpower_type_title == 'Consultant')
                    <?php $member = PMIS\AuthorizedPerson::find($id);?>
                @elseif($manpower_type_title == 'Contractor')
                    <?php $member = PMIS\AuthorizedPerson::find($id);?>
                @endif
                ({{$member_index+1}}) {{ optional($member)->name }}
                <?php $attendence[] = $manpower['attendence'][$id] == 1 ? 'Present' : 'Absent'; ?>
            @endforeach
        @endif
    @else
        @if(is_array($manpower))
            @foreach($manpower as $sm => $mt)
                <p class="small">{{ $sm }}/{{ $mt['unit'] }}</p>
            @endforeach
        @endif
        @else
        
        @endif
</td>--}}
<td width="20%">
    {{--                                                                @if(isset($manpower['quantity']))--}}
    {{--                                                                    {{$manpower['quantity']}}--}}
    {{--                                                                @elseif(isset($manpower['id']) && $attendence)--}}
    {{--                                                                    {{ json_encode($attendence) }}--}}
    {{--                                                                @else--}}
    {{--                                                                    @if(is_array($manpower))--}}
    {{--                                                                        @foreach($manpower as $sm => $mt)--}}
    {{--                                                                            <p>{{ $mt['quantity'] }}</p>--}}
    {{--                                                                        @endforeach--}}
    {{--                                                                    @endif--}}
    {{--                                                                @endif--}}


</td>


@foreach(['for_client', 'for_consultant', 'for_contractor'] as $index)
    @if(in_array($index, $sample))
        @foreach($sample[$index] as $ids)
            @foreach($ids as $id)
                <li>{{dd(\PMIS\Engineer::find($id)->name)}}</li>
            @endforeach
        @endforeach
        @endforeach