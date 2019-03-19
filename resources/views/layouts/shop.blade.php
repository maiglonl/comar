<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Scripts -->
	@yield('pre-scripts')
	<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
	<div class="load-page"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>
	@include('app.menu')

	<main>
	@yield('body_top')

		<div class="container-fluid min-100 align-items-stretch">
			<div class="row justify-content-md-center">
				<div class="col-md-12">
					@yield('content')
				</div>
			</div>
		</div>

		@yield('scripts')

	</main>
</body>
</html>
