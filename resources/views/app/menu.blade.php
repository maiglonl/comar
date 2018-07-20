<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
	<div class="container">
		@if(Auth::user())
			<a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
		@else
			<a class="navbar-brand" href="{{ route('index') }}">{{ config('app.name', 'Laravel') }}</a>
		@endif
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<!-- Left Side Of Navbar -->
			<ul class="navbar-nav mr-auto">
			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="navbar-nav ml-auto">
				<!-- Authentication Links -->
				@guest
					<li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
					<li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
				@else

					<li><a class="nav-link" href="{{ route('products.shop') }}">Shop</a></li>
					@if(Auth::user()->role == USER_ROLES_ADMIN) 
						<li><a class="nav-link" href="{{ route('products.index') }}">Produtos</a></li>
					@endif
					<li><a class="nav-link" href="{{ route('users.index') }}">Usuários</a></li>
					<li class="nav-item dropdown">
						<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
							{{ Auth::user()->name }} <span class="caret"></span>
						</a>

						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="{{ route('logout') }}"
							   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								{{ __('Logout') }}
							</a>

							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</div>
					</li>
					<li><a class="nav-link" href="{{ route('orders.cart') }}"><i class="fas fa-shopping-cart"></i></span></a></li>
				@endguest
			</ul>
		</div>
	</div>
</nav>