<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('public/images/favicon.png')}}" type="image/x-icon" />
    <link href="{{asset('/public/admin/css/proStyle.css') }}" rel="stylesheet">
    <link href="{{ asset('/public/admin/css/pro_sweet-alert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/plugin/spinner/spinners.css') }}" type="text/css">
    <link href="{{ asset('/public/admin/css/animate.min.css') }}" rel="stylesheet">

    <base href="{{route('home')}}" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('header')
</head>
<body>
    @yield('content')
    <!-- Scripts jquery-2.1.1.min-->
    <script src="{{ asset('/public/js/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('/public/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('public/js/jquery.appear.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/jquery.sticky.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/admin/js/sweet-alert.min.js')}}"></script>
    <script src="{{asset('public/js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/plugin/datepicker/nepali.datepicker.v2.1.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if(isset($delete_success_info))
            delete_success_info(<?php echo $delete_success_info; ?>);
            @endif

            @if(isset($update_success_info))
            update_success_info(<?php echo $update_success_info; ?>);
            @endif

            @if(isset($update_unsuccess_info))
            update_unsuccess_info(<?php echo $update_unsuccess_info; ?>);
            @endif

            @if(isset($store_success_info))
            store_success_info(<?php echo $store_success_info; ?>);
            @endif

            @if(isset($delete_file_success_info))
            delete_file_success_info(<?php echo $delete_file_success_info; ?>);
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
        });


    </script>
    @yield('footer')
</body>
</html>