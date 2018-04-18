<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />

	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
	<title>{{ strip_tags($course->headertext) }}</title>

	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon.png">

</head>

<body>
	<div class="corner-ribbon top-left sticky red shadow">Sniktitt</div>

	<div id="app" class="container-fluid" style="padding: 0" data-courseid="{{ $course->id }}">

		<div class="uio-banner" style="background-image:url(resources/{{ $course->header }})">
			<div class="container-fluid">
				<h1>{!! $course->headertext !!}</h1>
			</div>
		</div>

		@foreach ($course->modules as $id => $module)
		<course-module class="module" course-id="{{ $course->id }}" module-id="{{ $id }}" image="resources/{{ $module->image }}" name="{{ $module->name }}">
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
		MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
	</script>
	<script type="text/javascript" async
	src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
</body>

</html>
