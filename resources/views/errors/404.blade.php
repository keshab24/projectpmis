0<html>
	<head>
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
        <link href="{!! asset('public/admin/css/all.css') !!}" rel="stylesheet" type="text/css">
		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
                margin-top: 15%;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 72px;
				margin-bottom: 40px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">
                    Requested Page Not Found!! <br />
                    <a href="{!! URL::previous() !!}" title="Go Back"><span class="glyphicon glyphicon-circle-arrow-left"></span>BACK</a>
                </div>
			</div>
		</div>
	</body>
</html>
