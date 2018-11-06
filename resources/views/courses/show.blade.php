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
	<title>{{ strip_tags($course->header->text) }}</title>

	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon.png">

</head>

<body>
	<!--<div class="corner-ribbon top-left sticky red shadow">Sniktitt</div>-->

    <div class="uio-header">
        <div class="container-fluid">
            <a href="https://www.uio.no/" title="Universitetet i Oslo"><img src="/images/yndlingsuniversitetet-ditt.svg" alt="Universitetet i Oslo"></a>
            <!-- Dette ser kanskje litt teit ut, men vi er pålagt å ha det her -->
        </div>
    </div>

	<div id="app" data-courseid="{{ $course->id }}">
        <div class="container-fluid">

		<header class="padded" style="background-image:url(resources/{{ $course->header->background }})">
			<h1><a href="./" id="top">{!! $course->header->text !!}</a></h1>
		</header>

		@if ($course->option('show_privacy_statement'))
		<p class="text-small text-muted padded">
			Personvern: Dette er et anonymt nettkurs.
			Vi lagrer svar på oppgaver for statistikkformål og for å kunne forbedre kurset,
			men knytter de ikke til brukerkonto, IP-adresse eller noe annet
			som kan identifisere deg.<!-- For mer informasjon, se <a href="/personvern">personvern</a>.-->
		</p>
		@endif

    <course-modules :course-id="{{ $course->id }}" :modules="{{ json_encode($course->modules) }}"></course-modules>
	</div>

  <footer class="page-footer font-small blue pt-4 pb-3">
    <div class="container-fluid text-center text-md-left">

      <!-- Column -->
      <div class="logo" ></div>

      <!-- Column -->
      <div>
        <h5>{{ $course->domain ?? strip_tags($course->header->text) }}</h5>
        <ul class="list-unstyled">
          <li>
            Et minikurs fra {!! $publishers !!}
          </li>
          <li>
            Sist oppdatert {{ $course->updated_at->formatLocalized('%d. %B %Y') }}
          </li>
          <li>
            Drevet av <a href="https://github.com/uio-library/scroll-app" title="Scroll">Scroll</a>
          </li>
        </ul>
      </div>

      <!-- Column -->
      <div>
        <h5 class="d-none d-md-block">&nbsp;</h5>
        <ul class="list-unstyled">
          @foreach(($course->footer->credits ?? []) as $agent)
          <li>
            {{ $agent->role }}:
            <a href="{{ $agent->link }}" title="{{ $agent->label }}">{{ $agent->label }}</a>
          </li>
          @endforeach
        </ul>
      </div>

      <!-- Column -->
      <div class="ft-col-3">
        <h5 class="d-none d-md-block">&nbsp;</h5>
        <ul class="list-unstyled">
          <li>
            Ansvarlig for nettstedet:<br>
            <a href="{{ $course->footer->editor->link }}" title="{{ $course->footer->editor->label }}">{{ $course->footer->editor->label }}</a>
          </li>
        </ul>
      </div>

    </div>
  </footer>
  </div>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML"></script>

	<script src="{{ mix('js/manifest.js') }}"></script>
	<script src="{{ mix('js/vendor.js') }}"></script>
	<script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
