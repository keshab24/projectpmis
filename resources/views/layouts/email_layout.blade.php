<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    Dear {{$user?$user->name:""}}, <br/><br/>
    @yield('content')
    <img src="{{ asset('public/images/home_banner.png') }}" alt="">
    <a href="https://goo.gl/phKofj">
        Please download and use our Mobile App
    </a>
    <p>
        Project Management Information System (PMIS)  - <a href="{{ route('home') }}">here</a> (Web based software for easy and quick data management)
    </p>
    <img src="https://lh5.googleusercontent.com/-OmVnpHI5fZs/U0pdZ2bfDUI/AAAAAAAAACU/jq0NRAOZuSI/w75-h101/np-flag1-ss.gif" alt="">
</body>
</html>
