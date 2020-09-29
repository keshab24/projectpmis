<!doctype html>
<html lang="np">
<head>
    <script type="text/javascript" src="{{asset('public/js/numtowords.js')}}"></script>
    <meta charset="UTF-8">
    <title>ProAC Letter</title>
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
</head>
<body>
    <table style="width:7.5in">
        <tr>
            <td align="right">
                मिति: {{dateBS($payment->release_date)}}
            </td>
        </tr>
        <tr>
            <td>
                श्री {{$payment->release()->first()->project->implementing_office->name}}<br>
                @if($payment->release()->first()->project->implementing_office->address != '')
                    {{$payment->release()->first()->project->implementing_office->address}},
                @endif
                @if($payment->release()->first()->project->implementing_office->district)
                    {{$payment->release()->first()->project->implementing_office->district->name}}
                @endif
            </td>
        </tr>
        <tr>
            <td align="center">
                विषय : रकम निकासा सम्बन्धमा |
            </td>
        </tr>
        <tr>
            <td>
                <p align="justify">
                उपरोक्त सम्बन्धमा तहाँ कार्यालयबाट माग भई आए अनुसार आ.व. <span class="kanji">{{$payment->fiscal_year->fy}}</span> मा चालु खर्छा ब.शि.नं ३३७१६५३ (बजेट रकम नं २६९४२१) अन्तर्गत सडक मर्मत सम्भार गर्नको लागि देहाय अनुसारको शिर्षकमा <strong>{{rank_nepali($payment->release()->first()->project->releases->count())}}</strong> किस्ता वापत रकम रु <strong>{{$payment->release()->sum('amount')}} (अक्षरेपी <span id="words"></span> मात्र)
                    </strong>, <strong>
                    @if($payment->payment_method == 'FCGO')
                        {{$payment->payment_detail}}
                    @elseif($payment->payment_method == 'Account Payee')
                        माछापुच्छ्रे बैंक लिमिटेडको एकाउन्ट पेयी चेक नं {{$payment->cheque_no}}
                    @else
                        @if($payment->release()->first()->project->implementing_office->bank_name !='')
                            {{$payment->release()->first()->project->implementing_office->bank_name}} {{$payment->release()->first()->project->implementing_office->branch_address}} मा रहेको खाता नं  {{$payment->release()->first()->project->implementing_office->account_no}}
                        @endif
                    @endif
                </strong> मा दाखिला गरि निकासा पठाईएको व्यहोरा अनुरोध छ | रकम प्राप्त भएपछि स्वीकृत कार्यक्रम अनुसारको सडक मर्मत कार्य दिगो, भरपर्दो एवं प्रभावकारी ढंगले गर्नु भै सो को आर्थिक विवरण र लेखा परिक्षण प्रतिवेदन समेत समयमानै उपलब्ध गरिदिनुहुन निर्देशानुसार अनुरोध छ |
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <table border="1" cellspacing="0" cellpadding="5">
                    <thead>
                    <tr>
                        <th>सी नं</th>
                        <th>आ.व</th>
                        <th>बजेट शिर्षक</th>
                        <th>स्वीकृत बजेट रु</th>
                        <th>यस अघि निकाशा भएको बजेट रु</th>
                        <th>हाल निकाशा भएको बजेट रु</th>
                        <th>जम्मा निकासा रु</th>
                        <th>कैफियत</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 1;
                            $total_budget = 0;
                            $total_previous = 0;
                            $total_amount = 0;
                            $total_current = 0;
                    ?>
                    @foreach($payment->release as $release)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$payment->fiscal_year->fy}}</td>
                            <td>{{$release->project->name}}</td>
                            <td class="right">{{$total_budget += $release->project->procurement->estimated_amount}}</td>
                            <td class="right">{{$total_previous += $release->project->releases()->where('id','<',$release->id)->sum('amount')}}</td>
                            <td class="right">{{$total_amount += $release->amount}}</td>
                            <td class="right">{{$total_current += $release->project->releases()->sum('amount')}}</td>
                            <td>{{rank_nepali($release->project->releases->count())}} किस्ता</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>जम्मा</th>
                        <th class="right">{{$total_budget}}</th>
                        <th class="right">{{$total_previous}}</th>
                        <th class="right">{{$total_amount}}</th>
                        <th class="right">{{$total_current}}</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right">
                <br><br><br><br><br>
                -------------------------------<br>
                (निर्मल प्रसाद कोइराला)<br>
                बरिष्ठ बित्ता तथा लेखा अधिकृत

                <input type="hidden" id="number" value="{{$payment->release()->sum('amount')}}" name="number">
            </td>
        </tr>
    </table>
<script type="text/javascript">
    document.getElementById('words').innerHTML = translate_nepali_num_into_words(document.getElementById('number').value);

</script>
</body>
</html>
