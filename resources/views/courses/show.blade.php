<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />-->

    <link href="https://fonts.googleapis.com/css?family=PT+Serif:400,400i&amp;subset=latin-ext" rel="stylesheet">
    <!--<link href="https://fonts.googleapis.com/css?family=Lora:400,400i&amp;subset=latin-ext" rel="stylesheet">-->

	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
	<title>{{ strip_tags($course->headertext) }}</title>

	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon.png">

</head>

<body>
	<!--<div class="corner-ribbon top-left sticky red shadow">Sniktitt</div>-->

	<div id="app" class="container-fluid" style="padding: 0" data-courseid="{{ $course->id }}">

		<div class="uio-banner" style="background-image:url(resources/{{ $course->header }})">
			<div class="container-fluid">
				<h1>{!! $course->headertext !!}</h1>
			</div>
		</div>

		<div class="container-fluid">
			@if ($course->option('show_privacy_statement'))
			<p class="text-small text-muted">
				Personvern: Dette er et anonymt nettkurs.
				Vi lagrer svar på oppgaver for statistikkformål og for å kunne forbedre kurset,
				men knytter de ikke til brukerkonto, IP-adresse eller noe annet
				som kan identifisere deg.<!-- For mer informasjon, se <a href="/personvern">personvern</a>.-->
			</p>
			@endif
		</div>

		@foreach ($course->modules as $id => $module)
		<course-module class="module" course-id="{{ $course->id }}" module-id="{{ $id }}" image="resources/{{ $module->image }}" :image-aspect-ratio="{{ isset($module->imageaspectratio) ? $module->imageaspectratio : 4 }}" name="{{ $module->name }}">
			{!! $module->html !!}
		</course-module>
		@endforeach
	</div>

	<div id="footer">
		<div class="container-fluid" style="background-image:url(resources/{{ $course->footer }})"></div>
	</div>

	<script src="{{ mix('js/manifest.js') }}"></script>
	<script src="{{ mix('js/vendor.js') }}"></script>
	<script src="{{ mix('js/app.js') }}"></script>

	<script type="text/x-mathjax-config">
		MathJax.Hub.Config({
			tex2jax: {
				inlineMath: [['$','$'], ['\\(','\\)']]
			},
	        CommonHTML: {
			    scale: 93
			},
		});
	</script>
	<script type="text/javascript" async
	src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
</body>

</html>
