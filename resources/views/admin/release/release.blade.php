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
                <li class="active"><a href="{{route('release.index')}}"><span class="glyphicon glyphicon-list"></span> Release</a></li>
            </ol>

            <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="clearfix"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Choose Implementing Office</h3>
                                {!! minimizeButton('pro_collapse_implementing_office') !!}
                            </div>
                            <div class="panel-body collapse in implementing_office_show" id="pro_collapse_implementing_office">
                                <ul>
                                    @foreach($implementingoffices as $implementingoffice)
                                        <li>
                                            @if($implementingoffice->is_physical_office ==1)
                                            <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                            @else
                                                <a href="#">{{$implementingoffice->name}}</a>
                                            @endif
                                            @if($implementingoffice->child()->count()>0)
                                                <ul>
                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                        <li>
                                                            @if($implementingoffice->is_physical_office ==1)
                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                            @else
                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                            @endif
                                                            @if($implementingoffice->child()->count()>0)
                                                                <ul>
                                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                                        <li>
                                                                            @if($implementingoffice->is_physical_office ==1)
                                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                            @else
                                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                                            @endif
                                                                            @if($implementingoffice->child()->count()>0)
                                                                                <ul>
                                                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                                                        <li>
                                                                                            @if($implementingoffice->is_physical_office ==1)
                                                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                                            @else
                                                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                                                            @endif
                                                                                            @if($implementingoffice->child()->count()>0)
                                                                                                <ul>
                                                                                                    @foreach($implementingoffice->child as $implementingoffice)
                                                                                                        <li>
                                                                                                            @if($implementingoffice->is_physical_office ==1)
                                                                                                                <a href="{{route('implementingoffice.release',$implementingoffice->id)}}">{{$implementingoffice->name}}</a>
                                                                                                            @else
                                                                                                                <a href="#">{{$implementingoffice->name}}</a>
                                                                                                            @endif
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
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-8">
                        {!! Form::open(['route'=>['implementingoffice.release.post',$implementing_office->id],'method'=>'post','class'=>'showSavingOnSubmit','files'=>true]) !!}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><i class="fa fa-money"></i> पेस्की तथा निकाशको विवरण</h3>
                                {!! minimizeButton('pro_collapse_implementing_office') !!}
                            </div>
                            <div class="panel-body collapse in implementing_office_show" id="pro_collapse_implementing_office">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select/Deselect all items">
                                                    <input type="checkbox" name="select_all" id="select_all" class="sixth_color" />
                                                    <label for="select_all"></label>
                                                </div>
                                            </th>
                                            <th>कार्यक्रम/ आयोजना</th>
                                            <th>जम्मा बजेट</th>
                                            <th>हालसम्मको निकासा</th>
                                            <th>नयाँ रकम</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;?>
                                    @foreach($implementing_office->projects as $project)
                                        <tr>
                                            <td><div class="form-group col-md-12 col-lg-12 pro_checkbox showToolTip" title="Select">
                                                    <input type="checkbox" name="select_item[]" id="select_item{{$i}}" class="select_me third_color" data-menu-id="{{$project->id}}" />
                                                    <label for="select_item{{$i++}}"></label>
                                                </div></td>
                                            <td>
                                                {{$project->construction_type->name}}<br>
                                                <strong>{{$project->name}}</strong>
                                            </td>
                                            <td>
                                                {{$project->procurement->estimated_amount}}
                                            </td>
                                            <td>
                                                {{$project->releases->sum('amount')}}
                                            </td>
                                            <td>
                                                <input type="hidden" name="project_ids[]" value="{{$project->id}}">
                                                <input type="text" class="form-control amount" data-total-budget="{{$project->procurement->estimated_amount-$project->releases()->sum('amount')}}" placeholder="Peski Amount" name="amount[]">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>

                                <div>
                                    <!-- release_date -->
                                    <div class="form-group col-md-4 col-lg-4 @if($errors->has('release_date')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('release_date','निकासा मिति:') !!}
                                            {!! Form::text('release_date', dateBS(date('Y-m-d')), ['class'=>'form-control']) !!}
                                            @if($errors->has('release_date'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="release_dateStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('release_date') !!}</small></div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="release_dateStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- payment_method -->
                                    <div class="form-group col-md-4 col-lg-4 @if($errors->has('payment_method')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('payment_method','भुक्तानीको तरिका:') !!}
                                            {!! Form::select('payment_method', ['Account Payee'=>'Account Payee','Bank Deposit'=>'Bank Deposit','FCGO'=>'FCGO'],null, ['class'=>'form-control']) !!}
                                            @if($errors->has('payment_method'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="payment_methodStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('payment_method') !!}</small></div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="payment_methodStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- cheque_no -->
                                    <div class="form-group cheque_no col-md-4 col-lg-4 @if($errors->has('cheque_no')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('cheque_no','चेक नं:') !!}
                                            {!! Form::text('cheque_no', null, ['class'=>'form-control']) !!}
                                            @if($errors->has('cheque_no'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="cheque_noStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('cheque_no') !!}</small></div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="cheque_noStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- payment_detail -->
                                    <div class="form-group payment_detail col-md-4 col-lg-4 @if($errors->has('payment_detail')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('payment_detail','कोलेनिका खाता विवरण:') !!}
                                            {!! Form::text('payment_detail', null, ['class'=>'form-control']) !!}
                                            @if($errors->has('payment_detail'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="payment_detailStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('payment_detail') !!}</small></div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="payment_detailStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- fund_store_id -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('fund_store_id')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('fund_store_id','Paid From:') !!}
                                            {!! Form::select('fund_store_id', $fund_stores,0, ['class'=>'form-control']) !!}
                                            @if($errors->has('fund_store_id'))
                                                <span class="glyphicon glyphicon-remove-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="fund_store_idStatus" class="sr-only">(error)</span>
                                                <div class="alert alert-danger"><small><i class="fa fa-warning"></i> {!! $errors->first('fund_store_id') !!}</small></div>
                                            @elseif(count($errors->all())>0)
                                                <span class="glyphicon glyphicon-ok-circle form-control-feedback" aria-hidden="true"></span>
                                                <span id="fund_store_idStatus" class="sr-only">(success)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- description -->
                                    <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                        <div class="input-group pro_make_full showToolTip">
                                            {!! Form::label('description','Statement:') !!}
                                            {!! Form::text('description', null, ['class'=>'form-control']) !!}
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
                            <div class="panel-footer text-right">
                                <button role="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>


                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>
@stop

@section('footerContent')
<script type="text/javascript" src="{{asset('public/admin/js/check_all.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/plugin/lightbox/js/lightbox.js')}}"></script>
<script type="text/javascript">
    $('document').ready(function() {
        $('.payment_detail').hide();
        $('#payment_method').change(function(){
            paymentChange($(this));

        });

        $('.showSavingOnSubmit').submit(function(){
            var $return = true;
            var $btn = $(this).find('button:submit');
           $('.amount').each(function(){
              $total_budget = Number(($(this).attr('data-total-budget')));
               if($(this).val()>$total_budget){
                   alert("The value can't exceed available budget, please change amount.");
                   $(this).select().focus();
                   $return = false;
                   $btn.removeAttr('disabled');
                   $btn.button('reset');
                   return false;
               }
           });
            return $return;
        });


        $('#release_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10 // Options | Number of years to show
        });
    });
    function paymentChange($box){
        if($box.val() == 'Account Payee'){
            $('.cheque_no').show();
            $('.payment_detail').hide();
        }else if($box.val() == 'FCGO'){
            $('.cheque_no').hide();
            $('.payment_detail').show();
        }else{
            $('.cheque_no').hide();
            $('.payment_detail').hide();
        }
    }
</script>

@stop