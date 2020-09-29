@extends('layouts.admin_default')


@section('headerContent')

@stop

@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('admin_home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                    <li><a href="{{route('pro_admin.account.index')}}"><span class="glyphicon glyphicon-book"></span> Account</a></li>
                    <li class="active"><span class="glyphicon glyphicon-random"></span> Account Delete</li>
                </ol>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <div class="col-md-12 col-lg-12">

                        <div class="alert alert-danger">
                            <h1><span class="glyphicon glyphicon-exclamation-sign"></span> WARNING !!</h1>
                            <small>These are the data which will be deleted as well if you force to delete the account.</small>
                            <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Warning Deletion Process!!" data-content="If you delete this account, the process will delete all the listed records as well because they all are related. Once it is deleted it cannot be recovered by anyone."><span class="glyphicon glyphicon-info-sign"></span></a>
                        </div>
                        <br />
                        <a href="{!! route('pro_admin.account.index') !!}" title="Go Back" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span></a> &nbsp;&nbsp;&nbsp;&nbsp;

                        <a href="#" class="btn btn-danger showToolTip confirmButton" title="Force Delete" data-placement="top" data-form-id="pro_my_form_vtx"><span class="glyphicon glyphicon-remove-sign"></span> Force Delete</a>

                        {!! hard_delete_form(['pro_admin.account.destroy',$account->id], 'pro_my_form_vtx') !!}

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th>S.N.</th><th>Title</th><th>Title In Nepali</th>
                                </tr>
                                <?php  $i=1; ?>
                                @foreach($confirm_data as $data)
                                    <tr>
                                        <td>{!! $i++ !!}.</td>
                                        <td>{!! $data->title !!}</td>
                                        <td>{!! $data->title_nep !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>

@stop


@section('footerContent')

@stop