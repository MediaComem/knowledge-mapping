<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('pageTitle') - {{ config('app.name') }} </title>

	<!-- Styles -->
  	<link href="https://fonts.googleapis.com/css?family=Karla:400,700" rel="stylesheet">
	<link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet">
    <style><?php include(public_path().'/css/main.css'); ?></style>
    <style><?php include(public_path().'/css/ldavis.css'); ?></style>
	<!-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> -->
  	<!-- <link type="text/css" href="{{ asset('css/main.css') }}" rel="stylesheet"> -->
  	<!-- <link type="text/css" href="{{ asset('css/ldavis.css') }}" rel="stylesheet"> -->


	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <!-- <link href="https://cdn.pydata.org/bokeh/release/bokeh-0.12.6.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.pydata.org/bokeh/release/bokeh-widgets-0.12.6.min.css" rel="stylesheet" type="text/css">

    <script src="https://cdn.pydata.org/bokeh/release/bokeh-0.12.6.min.js"></script>
    <script src="https://cdn.pydata.org/bokeh/release/bokeh-widgets-0.12.6.min.js"></script>
    <script src="https://cdn.pydata.org/bokeh/release/bokeh-api-0.12.6.min.js"></script> -->

	<script src="//code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
	@section('meta')
	@endsection
</head>

<body>

	<section class="main-content">
		@yield('content')
	</section>

	<footer class="text-center">
		KM v1.2.0 | 2019-{{date("Y")}}</span>
	</footer>

	<!-- Scripts -->
	<script type="module" src="{{ asset('js/main.js') }}"></script>
	<script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
