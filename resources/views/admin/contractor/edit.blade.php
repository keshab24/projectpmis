@extends('layouts.admin_layout')
@section('headerContent')
@stop
@section('content')
    <div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                    <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span>
                            Dashboard</a></li>
                    <li><a href="{{route(strtolower($title).'.index')}}"><i class="fa fa-buysellads"></i> Contractor</a>
                    </li>
                    <li class="active"><a href="{{route(strtolower($title).'.create')}}"><i class="fa fa-plus"></i>
                            Add Contractor</a></li>
                    <li><a href="{{ route('user.edit',$contractor->myUser->slug)}}"><i class="fa fa-eye"></i> User</a></li>
                </ol>

                <div class="pro_helpers" data-appear-animation="fadeInDown" data-appear-delay="800">
                    <div class="col-lg-6 col-md-6">
                        <a href="{{route(strtolower($title).'.index')}}" class="btn btn-info showToolTip"
                           title="Edit Contractor" role="button" data-placement="top"><span
                                    class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs hidden-sm">Show Contractors</span></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    {!!Form::model($contractor, ['route'=>[strtolower($title).'.update', $contractor->slug],'method'=>'put','class'=>'showSavingOnSubmit','files'=>true])!!}
                    <div class="col-md-8 col-lg-8" data-appear-animation="fadeInLeft" data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-plus"></span> Add
                                    Contractor</h3>
                                {!! minimizeButton('pro_collapse_contractor') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_contractor">
                                <div class="clearfix"></div>
                                <!-- name -->
                                <div class="form-group col-md-6 col-lg-6 @if($errors->has('name')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('name','Name:') !!}
                                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
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
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('address')) title="{!!$errors->first('address')!!}" @endif>
                                        {!! Form::label('address','Address:') !!}
                                        {!! Form::text('address', null, ['class'=>'form-control']) !!}
                                        @if($errors->has('address'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="addressStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
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

                                <!-- description -->
                                <div class="form-group col-md-12 col-lg-12 @if($errors->has('description')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback">
                                    <div class="input-group pro_make_full showToolTip">
                                        {!! Form::label('description','Description:') !!}
                                        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>4]) !!}
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
                            </div>
                        </div>

                    </div>


                    <div class="col-md-4 col-lg-4 pro_content_right" data-appear-animation="fadeInRight"
                         data-appear-delay="1200">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-eyedropper"></span> Add/Edit Files
                                </h3>
                                {!! minimizeButton('pro_collapse_stock') !!}
                            </div>
                            <div class="panel-body collapse in pro_images_browser" id="pro_collapse_stock">
                                <?php
                                    $i = 1;
                                ?>
                                @if($contractor->file)
                                    <?php
                                        $files = explode(',', $contractor->file);
                                    ?>
                                    @foreach($files as $file)

                                        <div class="col-md-6 col-lg-6 pro_images_section">
                                        <span class="glyphicon glyphicon-remove-circle closeButton"
                                              onclick="hideDiv($(this))">
                                        </span>
                                            <label for="file{{$i}}" class="noBorder"><img
                                                        src="{{asset('public/contractorFiles/'.$file)}}"
                                                        title="" alt=""/> </label>
                                            <input type="file" name="files[]" class="form-control col-md-3 col-lg-3"
                                                   id="file{{$i++}}" onchange="readURL(this)"/>

                                            <input type="hidden" name="oldImages[]" value="{!! $file !!}"
                                                   data-pro-name="useMeDelete"/>
                                            <input type="hidden" name="deleteMe[]" value="0" data-pro-name="deleteMe"/>

                                            <div class="clearfix"></div>

                                        </div>
                                    @endforeach
                                @endif
                                    <div class="col-md-6 col-lg-6 pro_images_section">
                                    <span class="glyphicon glyphicon-remove-circle closeButton"
                                          onclick="hideDiv($(this))"></span>
                                        <label for="file{{$i}}"><span class="glyphicon glyphicon-plus"></span> Add a file</label>
                                        <input type="file" name="files[]" class="form-control col-md-3 col-lg-3"
                                               id="file{{$i}}" onchange="readURL(this)" multiple/>
                                        <input type="hidden" name="oldImages[]" value="0"/>
                                        <div class="clearfix"></div>
                                    </div>
                            </div>
                            <div class="panel-footer">
                                <input type="button" name="addImage" id="addImage" value="Add More"
                                       onclick="addImages()" class="btn btn-sm btn-warning"/>
                                <input type="button" name="removeAllImage" id="removeAllImage" value="Remove All"
                                       onclick="removeImages()" class="btn btn-sm btn-danger"/>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="fa fa-image"></span> Image</h3>
                                {!! minimizeButton('pro_collapse_contractor_image') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_contractor_image">
                                <div class="pro_image_preview">
                                    @if($contractor->image !='' && $contractor->image != NULL && file_exists('public/images/contractor/original/orivtx'.$contractor->image))
                                        <img src="{{asset('public/images/contractor/original/orivtx'.$contractor->image)}}" alt="{{$contractor->image}}" title="{{$contractor->name}}" class="img img-responsive showToolTip" />
                                    @else
                                        <img src="{{asset('public/images/no_image_thumb.png')}}" alt="No Image" title="No Image Available!!" class="img-thumbnail showToolTip" />
                                    @endif
                                </div>
                                <!-- image -->
                                <label class="drop-zone form-group col-md-12 col-lg-12 @if($errors->has('image')) has-error @elseif(count($errors->all())>0) has-success @endif has-feedback"
                                       for="image_browse">
                                    <div class="input-group pro_make_full showToolTip"
                                         @if($errors->has('image')) title="{!!$errors->first('image')!!}" @endif>
                                        <span>Click here to select a image.</span>
                                        {!!Form::file('image', ['class'=>'form-control','id'=>'image_browse'])!!}
                                        @if($errors->has('image'))
                                            <span class="glyphicon glyphicon-remove-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="imageStatus" class="sr-only">(error)</span>
                                        @elseif(count($errors->all())>0)
                                            <span class="glyphicon glyphicon-ok-circle form-control-feedback"
                                                  aria-hidden="true"></span>
                                            <span id="imageStatus" class="sr-only">(success)</span>
                                        @endif
                                    </div>
                                    <img src="" id="image_preview" alt=""/>
                                </label>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-globe"></span>
                                    Publish</h3>
                                {!! minimizeButton('pro_collapse_contractor_save') !!}
                            </div>
                            <div class="panel-body collapse in" id="pro_collapse_contractor_save">
                                <!-- status -->
                                <div class="form-group col-md-6 col-lg-6 pro_checkbox pro_no_top">
                                    <input type="checkbox" name="status" id="status" class="first_color"
                                           checked="checked"/>
                                    <label for="status">Is Active?</label>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" data-loading-text="Saving..." autocomplete="off">
                                    Save
                                </button>
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
        var $i = {{$i+1}};
        function addImages() {
            $('.pro_images_section:last-child').after('<div class="col-md-6 col-lg-6 pro_images_section"> <span class="glyphicon glyphicon-remove-circle closeButton" onclick="hideDiv($(this))"></span> <label for="file' + $i + '"><span class="glyphicon glyphicon-plus"></span> Add a file</label> <input type="file" name="files[]" class="form-control col-md-6 col-lg-6" id="file' + $i + '" onchange="readURL(this)"  /> <input type="hidden" name="oldImages[]" value="0" /> <div class="clearfix"></div></div>');
            $i++;
        }
    </script>
    <script type="text/javascript" src="{!! asset('public/admin/js/media/media.js') !!}"></script>
@stop