<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />

	<link rel="stylesheet" href="{{ elixir('css/app.css') }}">
	<title>Litteratur og bibliotek</title>

</head>

<body>
	<div id="app">
		<div class="uio-banner" style="background-image:url({{ $course->header }})">
		</div> 
		
		<!-- <h2 class="header-text">
			{{ $course->headertext }}
		</h2> -->

		@foreach ($course->modules as $id => $module)
		<div class = "module">
			<div class="header" data-toggle="collapse" data-target="#{{ $id }}" style="background-image:url({{ $module->image }})">
				<div class="container-fluid">
					<h2 style="user-select: none;">{{ $module->name }}</h2>
				</div>
			</div>
			<div id="{{ $id }}" class="collapse container-fluid" style="margin-top : 10px">
				{!! $module->html !!}
				
			</div>
		</div>
		@endforeach
	</div>



	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="{{ elixir('js/app.js') }}"></script>
	<script type="text/javascript" async
	src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
</body>

</html>
