<!DOCTYPE html>

<style>
	.mainHeader {
		font-size: 10vw;
	}

	.moduleHeader {
		font-size: 8vw;
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
</style> 

<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<title>Litteratur og bibliotek</title>

</head>

<body>
	<div class=container>
		<h1 class="mainHeader">LITTERATUR OG BIBLIOTEK<br></h1>
		

		<div class="module">
			<div class="carousel" href="#biblioteket" data-toggle="collapse">
				<div class="carousel-item active">
					<img src="images/discoveredonceagain.jpg" class="img-fluid" >
					<div class="carousel-caption left-align">
						<h1 class=moduleHeader>1.0 Biblioteket</h1>
					</div>
				</div>
			</div>
			<div id="biblioteket" class="collapse hide" role="tabpanel" aria-labelledby="headingOne">
				@include('contents.biblioteket')
			</div>
		</div>


		<div class="module">
			<div class="carousel" href="#litteratursok" data-toggle="collapse">
				<div class="carousel-item active">
					<img src="images/discoveredonceagain.jpg" class="img-fluid" >
					<div class="carousel-caption">
						<h1 class=moduleHeader>1.1 Litteratursøk</h1>
					</div>
				</div>
			</div>
			<div id="litteratursok" class="collapse hide" role="tabpanel" aria-labelledby="headingOne">
				@include('contents.litteratursok')
			</div>
		</div>


		<div class="module">
			<div class="carousel" href="#referering" data-toggle="collapse">
				<div class="carousel-item active">
					<img src="images/discoveredonceagain.jpg" class="img-fluid" >
					<div class="carousel-caption">
						<h1 class=moduleHeader>1.2 Referanse&shy;håndtering</h1>
					</div>
				</div>
			</div>
			<div id="referering" class="collapse hide" role="tabpanel" aria-labelledby="headingOne">
				@include('contents.referering')
			</div>
		</div>


		<div class="module">
			<div class="carousel" href="#prove" data-toggle="collapse">
				<div class="carousel-item active">
					<img src="images/discoveredonceagain.jpg" class="img-fluid" >
					<div class="carousel-caption">
						<h1 class=moduleHeader>1.3 Prøve</h1>
					</div>
				</div>
			</div>
			<div id="prove" class="collapse hide" role="tabpanel" aria-labelledby="headingOne">
				@include('contents.test')
			</div>
		</div>
		

	</div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</body>

</html>
