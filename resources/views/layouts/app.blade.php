<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gram Time Tracking') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app" class="app">

        <header class="site-header">

			<div class="container">

				<a class="logo" href="{{ url('/shifts') }}">
					<img src="{{ url('/img/gram_text_logo.svg') }}" alt="Gram Logo">
				</a>

				<nav>

					<ul class="main-nav">
						@if (auth()->guest())
							<li><a href="{{ url('/login') }}">Login</a></li>
							<li><a href="{{ url('/register') }}">Register</a></li>
						@else
							<li><a href="{{ url('/shifts/create') }}">Log a shift</a></li>
							<li><a href="{{ url('/shifts') }}">All shifts</a></li>
							<li>
								<a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									Logout
								</a>
								<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
						@endif
					</ul>
				</nav>

			</div>
		</header>

		<main class="site-main container">
        	@yield('content')
		</main>

		<footer class="site-footer">

		</footer>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
