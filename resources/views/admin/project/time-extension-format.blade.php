@extends('layouts.admin_layout')


@section('headerContent')
	<style>
	.time-extension-letter{
		border:1px solid #000
	}	
	p{
		font-family: monospace
	}
	</style>
@stop

@section('content')
	@php
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
    @endphp
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" data-appear-animation="fadeInRight" data-appear-delay="600">
                <li><a href="{{route('home')}}"><span class="glyphicon glyphicon-blackboard"></span> Dashboard</a></li>
                <li class="active"><a href="{{route('user.index')}}"><i class="fa fa-buysellads"></i> Admin</a></li>
                <li class="active"><a href="{{route('user.index')}}"><i class="fa fa-buysellads"></i> Time Extension letter format</a></li>
            </ol>
        </div>
	</div>
	<button id="btnPrint" class="btn btn-info pull-right">Print</button>
	<div class="clearfix"></div>
    <div class="container time-extension-letter" id="dvData">
		<style>
		@media screen, print {
			p{
				margin: 5px;
				font-family: monospace
			}
			h3{
				text-align:center;
				font-weight:bold;
				margin-bottom:5px;
			}
			.np{
				font-family: 'Fontasy Himali'!important;
				font-size: small
			}
		}
		</style>
		<span style="float:right;" class="np">{{ $current_bs_date }}</span>
		<h3 style="margin-left:1cm;text-align: justify;">अपुग कागजातहरु</h3>
		<ul>
			@if ($apugKagajats)
				@foreach ($apugKagajats as $index=>$kagajat)
				<li>{{ $index+1 .'. '.$kagajat->title }}</li>
				@endforeach
			@endif
			
		</ul>
		<br>
		<h3>म्याद थप सम्बन्धमा ।</h3>
		<p>श्रीमान् उप-महानिर्देशकज्यू,</p>
		<p style="text-align:justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;प्रस्तुत विषयमा 
			<strong>
				शहरी विकास तथा भवन निर्माण विभाग अन्तर्गत 
				{{ optional($setting->implementing_office)->title }}बाट निर्माणाधीन 
				{{ $project->name}}
				निर्माण कार्य 
				{{ optional($setting->implementing_office)->name }}को 
			</strong>
			निम्नानुसारको ठेक्काको म्याद समाप्त भई पुनः म्याद थपको लागि पेश भएको फायल अध्ययन गर्दा निम्नानुसार विवरणहरु पाइयो ।</p>
		<p><strong>पेश भएका विवरणहरुः</strong></p>
		<?php 
			$contract_date = $project->procurement->contract_date_eng;
			$completion_date = $project->procurement->completion_date_eng;
			$project_period = ((strtotime($completion_date) - strtotime($contract_date)) / (60 * 60 * 24 * 30));

			$show = false;
			if ($project->procurement->Contractor != null) {
				$contractor = $project->procurement->Contractor->name;
				$route = "contractor.show";
				$slug = $project->procurement->Contractor->slug;
				$show = true;
			}
			if ($project->procurement->JointVenture != null) {
				$contractor = $project->procurement->JointVenture->name;
				$route = "joint_venture.edit";
				$slug = $project->procurement->JointVenture->slug;
				$show = true;
			}
		?>

		<p>सम्झौता मिति : <span class="np">{{ optional($project->procurement)->contract_date }}</span>	म्याद समाप्त हुने मिति : <span class="np">{{ optional($project->procurement)->completion_date }}</span>     शुरु सम्झौता अवधि :  <span class="np">	{{ number_format($project_period,0) }} </span>महिना</p>
		<p>निर्माण व्यवसायी : श्री {{ $contractor ?? null }}।</p>
		<p>यस अघि म्याद थप भएको अवधिः</p>
		<p>
			@foreach($project->timeExtension as $index => $time_extension)
			<?php 
				$start_date = $time_extension->start_date_eng;
				$end_date = $time_extension->end_date_eng;
				$extended_period = ((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24 * 30));
			?>
				<p>	{{ times()[$index+1]??'' }} पटक : मिति <span class="np">{{ $time_extension->start_date }}</span>  देखि   मिति <span class="np">{{ $time_extension->end_date }}</span>  सम्म - जम्मा <span class="np">{{ number_format($extended_period, 0) }} </span>महिना {{ verifiedFrom()[$time_extension->verified_from] ?? '' }} {{ $time_extension->liquidated_damage ? '(हर्जाना)' : '(बिना हर्जाना)' }}</p>
			@endforeach
		</p>
		<p>
			{{-- todo mag gariyeko duration and dates --}}
			थप म्याद माग भएको अवधि : मिति  <span class="np">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>	देखि मिति <span class="np">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>  सम्म - जम्मा <span class="np">&nbsp;&nbsp;&nbsp;</span> महिना 
		</p>
		<p>म्याद थप गर्न पर्नाको कारणहरु : (सम्बन्धित {{ optional($setting->implementing_office)->title }}को सिफारिस अनुसार) : 
		<br>
		{{-- reason goes here --}}
		</p>
		<div style="display:flex">
			<div style="width:33.33%">
				<p>हाल सम्म भएको कामको प्रगति : </p>
			</div>
			<div style="width:33.33%">
				<p>भौतिक प्रगति : <span class="np">{{ ($project->lastProgress)->current_physical_progress }} </span>%</p>
			</div>
			<div style="width:33.33%">
				<?php 
					$projectCost = $project->projectCost();
					if ($projectCost == 0) {
						$monetary_progress = 0;
					} else {
						$totalExpenditure = 0;
						$IdOffFiscalYearNow = PMIS\Fiscalyear::where('fy', explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[0] . '-' . explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[1])->first()->id;
						$old_progresses = $project->progresses()->where('month_id', 12)->where('fy_id', '<>', $IdOffFiscalYearNow)->get();
						$latestProgress = $project->progresses()->where('fy_id', $IdOffFiscalYearNow)->get();

						foreach ($old_progresses as $old_progress) {
							$totalExpenditure += $old_progress->bill_exp;
						}
						if ($latestProgress->count() != 0) {
							if ($latestProgress->last()->month_id == 12) {
								$totalExpenditure += $latestProgress->last()->bill_exp;
							} else {
								foreach ($latestProgress as $lastProgress) {
									$totalExpenditure += $lastProgress->bill_exp;
								}
							}
						}

						// $monetary_progress = floatval($totalExpenditure * 1000 / $projectCost * 100);
						$monetary_progress = number_format(($totalExpenditure / $projectCost) * 100);
					}

				?>
				<p>वित्तिय प्रगति : <span class="np">{{ $monetary_progress}}</span>%</p>
			</div>
		</div>
		<p>भौतिक प्रगतिको विवरण : {{ ($project->lastProgress)->project_remarks }} </p>
		<p>पेश भएको कार्य सम्पादन जमानी : <span class="np"></span></p>
		<p>अग्रीम भुक्तानी जमानी :  <span class="np"></span></p>
		<p>विमा अवधि :  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="np"></span> (Plus 12 months maintenance period)</p>
		<br><br>
		<p>उपरोक्त सम्बन्धमा म्याद थपको लागि निर्माण व्यवसायीले पेश गरेका कारणहरुमा .............................बाट सार्वजनिक खरिद नियमावली २०६४ को दफा १२० बमोजिम म्याद थप हुन सिफारिस पेश भएको छ ।
			निर्माण कार्यको भौतिक प्रगति .........रहेको र ................
			भनि मिति   .........................मा प्राविधिक प्रतिवेदन पेश भएको तथा निर्धारित समयमा नै निर्माण कार्य सम्पन्न हुन नसक्नुको म्भबिथ ब्लबथिकष्क सहितको कारणहरु पेश भएको छ ।
			सार्वजनिक खरिद नियामावली संसोदन अनुसार ५० प्रतिशत सम्मको म्याद थप सम्बन्धि मन्त्रालयको वा निकायको सचिबज्यूबाट हुने व्यवस्था भएकोले ...................... बाट सिफारिस पेश भए अनुसार मिति ........ देखि मिति .......... सम्म म्याद थप हुन निर्णयार्थ पेश गर्दछौ l साथै म्याद थप भय पश्चात निर्माण कार्यको दैनिक कार्य प्रगतिको अनुगमन, मुल्यांकन र म्याद भित्र कार्य सम्पन्न गर्न अन्य आवस्यक कारवाही .......... बताई गर्ने गरि निर्देशन हुन समेत राय पेश गर्दछौ l
		</p>
<p>
	<br><br><br><br><br><br><br><br>
</p>
    </div>
</div>


@stop

@section('footerContent')
<script src="{{ asset('public/js/jquery.printElement.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#btnPrint').click(function(){
                $('#dvData').show().printElement();
            })
        });
    </script>
@stop