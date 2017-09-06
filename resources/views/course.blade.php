<!DOCTYPE html>

<style>
	.mainHeader {
		font-size: 10vw;
	}

	.moduleHeader {
		font-size: 8vw;
		color : white;
	}


	.module .carousel:hover {
		opacity : 0.6;
		transition: opacity 0.3s;
	}

	.module .carousel {
		opacity : 1;
		transition: opacity 0.3s;
	} 

	body {
		padding-bottom: 6cm;
	}     
	.caption {
	    position: absolute;
	    top : 50%;
	    left: 10%;
	    width: 60%;
	    height: 100%;
	}

	.thumbnail {
		position: relative;
	}

</style> 

<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />

	<!--<link rel="stylesheet" href="{{ elixir('css/app.css') }}">-->
	<title>Litteratur og bibliotek</title>

</head>

<body>
	<div class="container" id="app">
		<h1 class="mainHeader">LITTERATUR OG BIBLIOTEK<br></h1>
		

		<div class = "module">
			<div class="thumbnail text-left" data-toggle="collapse" data-target="#biblioteket">
				<img src="images/discoveredonceagain.jpg" class="img-fluid" >
				<div class="caption"><h1 class="moduleHeader">1.0 Biblioteket<h1></div>
			</div>
			<div id="biblioteket" class="collapse">
				@include('contents.biblioteket')
			</div>
		</div>

		<div class = "module">
			<div class="thumbnail text-left" data-toggle="collapse" data-target="#litteratursok">
				<img src="images/discoveredonceagain.jpg" class="img-fluid" >
				<div class="caption"><h1 class="moduleHeader">1.1 Litteratursøk<h1></div>
			</div>
			<div id="litteratursok" class="collapse">
				@include('contents.litteratursok')
			</div>
		</div>

		<div class = "module">
			<div class="thumbnail text-left" data-toggle="collapse" data-target="#referering">
				<img src="images/discoveredonceagain.jpg" class="img-fluid" >
				<div class="caption"><h1 class="moduleHeader">1.2 Referanse&shy;håndtering<h1></div>
			</div>
			<div id="referering" class="collapse">
				@include('contents.referering')
			</div>
		</div>

		<div class = "module">
			<div class="thumbnail text-left" data-toggle="collapse" data-target="#prove">
				<img src="images/discoveredonceagain.jpg" class="img-fluid" >
				<div class="caption"><h1 class="moduleHeader">1.3 Prøve<h1></div>
			</div>
			<div id="prove" class="collapse">
				@include('contents.test')
			</div>
		</div>

	</div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="{{ elixir('js/app.js') }}"></script>
</body>

</html>
