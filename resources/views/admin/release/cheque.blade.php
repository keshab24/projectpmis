<!doctype html>
<html lang="np" class="no-js">

<head>

    <meta charset="UTF-8">
    <title>ProAdmin Letter</title>
    <style type="text/css">
        @font-face{
            font-family:kanjirowa;
            src:url('http://localhost/proAc/Kanjirowa.ttf');
        }
        *{
            font-size: 11pt;
            font-family: kanjirowa!important;
        }
        table table tr td,table table tr th{
            font-size: 8pt;
        }
        .right{
            text-align: right;
        }
        .kanji{
            font-family: Kanjirowa, kanjirawa !important;
        }
    </style>
    <script type="text/javascript" src="{{asset('public/js/numtowords.js')}}"></script>
</head>
<body>
    <table style="width:7.5in">
        <tr>
            <td align="right">
                {{dateBS($payment->release_date)}}
            </td>
        </tr>
        <tr>
            <td>
                {{$payment->release()->first()->project->implementing_office->name}},
                @if($payment->release()->first()->project->implementing_office->district)
                    {{$payment->release()->first()->project->implementing_office->district->name}}
                @endif
            </td>
        </tr>
        <tr>
            <td align="right">
                {{$payment->release()->sum('amount')}}
            </td>
        </tr>

        <tr>
            <td>
                <span id="words"></span> मात्र
            </td>
        </tr>
    </table>
    <input type="hidden" id="number" value="{{$payment->release()->sum('amount')}}" name="number">
    <script type="text/javascript">
        document.getElementById('words').innerHTML = translate_nepali_num_into_words(document.getElementById('number').value);
    </script>
</body>
</html>

