<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>ProAdmin :: PMIS</title>
    <link href="{{ asset('/admin/css/proStyleAdmin.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/admin/css/pro_checkbox.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('/admin/plugin/select2/css/select2.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/plugin/datepicker/nepali.datepicker.v2.1.min.css') }}" rel="stylesheet" type="text/css">
    <script>var $home_path = "{!! route('home') !!}";var $base_path = "{!! route('home') !!}";</script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-58851320-21"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-58851320-21');
    </script>
    @yield('headerContent')
</head>
<body>
    @include('partials._admin_header')
    <main class="cd-main-content" id="pro_main_container">
        @if(isset($recently_available_web_messages) && $recently_available_web_messages->count()>0)
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><span class="glyphicon glyphicon-envelope"></span> {{ $recently_available_web_messages->count() }} Message(s)</h3>
                {!! minimizeButton('pro_collapse_web_message') !!}
            </div>
            <div class="panel-body collapse" id="pro_collapse_web_message">
                @foreach($recently_available_web_messages as $message)
                    <div class="alert alert-{{$message->class_style}}">
                        <strong>
                            {{ $message->creator->implementingOffice->name }}
                            <br>
                            {{ $message->title }}
                        </strong>
                        <p>
                            {!! $message->description  !!}
                        </p>
                    </div>
                @endforeach

            </div>
        </div>
        @endif

        @yield('content')
    </main>
    @include('partials._admin_footer')
    <script src="{{asset('/admin/js/jquery-2.1.1.min.js')}}"type="text/javascript"></script>
    <script src="{{asset('/admin/js/bootstrap.min.js')}}"type="text/javascript"></script>
    <script src="{{asset('/admin/js/dropit.js')}}" type="text/javascript"></script>
    <script src="{{asset('/admin/plugin/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/admin/js/appear/jquery.appear.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/admin/js/nicescroll.js')}}" type="text/javascript"></script>
    <script src="{{asset('/admin/js/sweet-alert.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/admin/js/common.js')}}" type="text/javascript"></script>
    <script src="{{ asset('/admin/js/pusher.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('/plugin/datepicker/nepali.datepicker.v2.1.min.js')}}"></script>
    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                @if($pro_fiscal_year == false)
                    swal({
                        title: "Choose Fiscal Year",
                        text: "",
                        type: "input",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        allowOutsideClick:false,
                        inputPlaceholder: "Fiscal Year"
                    },
                    function(inputValue){
                        if (inputValue === false) return false;

                        if (inputValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }

                        $("#pro_fiscal_year_form").trigger('submit');
                        //swal("Nice!", "You wrote: " + inputValue, "success");
                    });
                    var $sweet_input = $(".sweet-alert input");
                    $sweet_input.val(2070);
                    $sweet_input.hide();
                    $sweet_input.after('{!! Form::open(["route"=>"set.fiscal.year","id"=>"pro_fiscal_year_form", "method"=>"get"]) !!}{!! Form::select("fiscal_year", $pro_fiscal_years, \PMIS\Fiscalyear::where('fy',getFiscalyearFromDate(dateBS(date('Y-m-d')),'-'))->first()->id, ["class"=>"form-control input","onchange"=>"fill_input($(this).val())"]) !!}{!! Form::close() !!}');
                @endif

                @if(isset($delete_success_info))
                delete_success_info(<?php echo $delete_success_info; ?>);
                @endif

                @if(isset($update_success_info))
                update_success_info(<?php echo $update_success_info; ?>);
                @endif

                @if(isset($update_unsuccess_info))
                update_unsuccess_info(<?php echo $update_unsuccess_info; ?>);
                @endif


                @if(isset($something_went_wrong))
                something_went_wrong(<?php echo $something_went_wrong; ?>);
                @endif

                @if(isset($store_success_info))
                store_success_info("<?php echo $store_success_info; ?>");
                @endif

                @if(isset($delete_file_success_info))
                delete_file_success_info("<?php echo $delete_file_success_info; ?>");
                @endif

                @if(isset($redirect_to))
                redirect_to(<?php echo $redirect_to; ?>);
                @endif

                @if(isset($page_linker))
                page_linker(<?php echo $page_linker; ?>);
                @endif

                @if(isset($restore_info))
                restore_info(<?php echo $restore_info; ?>);
                @endif

                @if(isset($fail_info))
                fail_info(<?php echo $fail_info; ?>);
                @endif
            });
        }) (jQuery);
        function fill_input($val){
            $(".sweet-alert input").val($val);
        }
    $('select').not('.selectize').select2();
    $('select').removeClass('form-control');


        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-58851320-18', 'auto');
        ga('send', 'pageview');
    $('.nepaliDate').attr('autocomplete','off')
    </script>

    @yield('footerContent')
</body>
</html>