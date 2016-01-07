<!doctype html>
<html lang="en">

<head>
	<!-- START: meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- title tag -->
	<!-- http://www.netlingo.com/tips/html-code-cheat-sheet.php -->
	<title>Site Not Available &verbar; {{{ Config::get('lasallecmsfrontend.site_name') }}}</title>



	<!-- Bootstrap -->
	<!-- from http://getbootstrap.com/getting-started -->

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>


	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


    <!-- Custom styles -->
	<style>
		body
		{
			/* background: url('under-the-lion.jpg') fixed; */
            background-color: #5c5cd6;
			background-size: cover;
			padding: 0;
			margin: 0;
		}

		.panel-heading
		{
			text-align: center;
			color: #FFFFFF;
			letter-spacing: 4px;
			font-size: 20px;
			font-weight: 600;

		}

		.input-group
		{
			width: 350px;
		}

		form {
			margin: 0 auto;
			width:350px;
		}
	</style>

</head>

<body>

<div class="container">

	<div class="col-sm-offset-2 col-sm-8" style="margin-top:200px;">
		<div class="panel panel-default">

			<div class="panel-heading">
				{{{ Config::get('lasallecmsfrontend.site_name') }}}
			</div>

			<div class="panel-body text-center">

				<br />

					Site not available at this time.

                <br /><br />


			</div>

		</div>

	</div>
</div>


</body>
</html>