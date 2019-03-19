<style type="text/css">
	#sidebar {
		z-index: 10;
		min-width: 250px;
		max-width: 250px;
		min-height: 90vh;
		transition: all 0.6s cubic-bezier(0.945, 0.020, 0.270, 0.665);
		transform-origin: center left;
	}
	#sidebar .sidebar-header {
		padding: 40px 10px 40px 15px;
	}
	#sidebar ul li.first-level > a {
		padding: 10px 10px 10px 15px;
		display: block;
	}
	#sidebar ul li.first-level > a:hover{
		transition: all 0.4s ease-in-out;
		background-color: #dee2e6;
		box-shadow: inset 4px 0 0 0px #5C66FC;
		cursor: pointer;
	}
	#sidebar li.child{
		padding: 5px 10px 0px 58px;
	}
	#sidebar li.child a:hover{
		color: #5C66FC;
		cursor: pointer;
	}
	#sidebar i{
		padding: 5px 10px 5px 5px;
	}
	#sidebar .active i{
		color: #5C66FC;
	}
	#sidebar .active span{
		font-weight: bold;
	}
	#sidebar ul li a {
		text-decoration: none;
		color: #686c73;
		display: block;
	}
	#sidebar ul li a span{
		font-size: 0.9rem;
	}
	#sidebar.active {
		margin-left: -250px;
		transform: rotateY(100deg);
	}
	a[data-toggle="collapse"] {
		position: relative;
	}
	#sidebar .dropdown-toggle::after {
		display: block;
		position: absolute;
		top: 50%;
		right: 20px;
		transform: translateY(-50%);
	}
	#sidebarCollapse {
		width: 40px;
		height: 40px;
		border:none;
	}
	#sidebarCollapse span {
		width: 80%;
		height: 2px;
		margin: 0 auto;
		display: block;
		background: #555;
		transition: all 0.8s cubic-bezier(0.810, -0.330, 0.345, 1.375);
	}
	#sidebarCollapse span:first-of-type {
		transform: rotate(45deg) translate(2px, 2px);
	}
	#sidebarCollapse span:nth-of-type(2) {
		opacity: 0;
	}
	#sidebarCollapse span:last-of-type {
		transform: rotate(-45deg) translate(1px, -1px);
	}
	#sidebarCollapse.active span {
		transform: none;
		opacity: 1;
		margin: 5px auto;
	}
	@media (max-width: 768px) {
		#sidebar {
			margin-left: -250px;
			transform: rotateY(100deg);
		}
		#sidebar.active {
			margin-left: 0;
			transform: none;
		}
		#sidebarCollapse span:first-of-type,
		#sidebarCollapse span:nth-of-type(2),
		#sidebarCollapse span:last-of-type {
			transform: none;
			opacity: 1;
			margin: 5px auto;
		}
		#sidebarCollapse.active span {
			margin: 0 auto;
		}
		#sidebarCollapse.active span:first-of-type {
			transform: rotate(45deg) translate(2px, 2px);
		}
		#sidebarCollapse.active span:nth-of-type(2) {
			opacity: 0;
		}
		#sidebarCollapse.active span:last-of-type {
			transform: rotate(-45deg) translate(1px, -1px);
		}
	}
</style>

<nav id="sidebar">
	<div class="sidebar-header">
		<i class="icon-menu icons h4 align-middle"></i>
		<span class="align-middle">Categorias</span>
	</div>

	<ul class="list-unstyled components">
		<li class="first-level {!! \App\Helpers\NavHelper::classActivePath(['home']) !!}">
			<a href="{{ route('home') }}" class="">
				<i class="icon-chart icons h4 align-middle"></i>
				<span class="align-middle">Resumo</span>
			</a>
		</li>
		<li class="first-level ">
			<a href="#" class="">
				<i class="icon-docs icons h4 align-middle"></i>
				<span class="align-middle">Faturas</span>
			</a>
		</li>
		<li class="first-level {!! \App\Helpers\NavHelper::classActivePath(['users.network']) !!}">
			<a href="{{ route('users.network') }}">
				<i class="icon-organization icons h4 align-middle"></i>
				<span class="align-middle">Minha rede</span>
			</a>
		</li>
		<li class="first-level {!! \App\Helpers\NavHelper::classActivePath(['orders.list']) !!}">
			<a href="#comprasSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
				<i class="icon-wallet icons h4 align-middle"></i>
				<span class="align-middle">Compras</span>
			</a>
			<ul class="collapse list-unstyled" id="comprasSubmenu">
				<li class="child"><a href="{{ route('orders.list') }}">Compras</a></li>
				<li class="child"><a href="#">Favoritos</a></li>
				<li class="child"><a href="#">Perguntas</a></li>
				<li class="child"><a href="#">Cotações</a></li>
				<li class="child"><a href="#">Assinaturas</a></li>
				<li class="child"><a href="#">Arremates</a></li>
			</ul>
		</li>
		<li class="first-level {!! \App\Helpers\NavHelper::classActivePath(['colab.home']) !!}">
			<a href="#configSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
				<i class="icon-settings icons h4 align-middle"></i>
				<span class="align-middle">Resumo</span>
			</a>
			<ul class="collapse list-unstyled" id="configSubmenu">
				<li class="child"><a href="#">Meus dados</a></li>
				<li class="child"><a href="#">Segurança</a></li>
				<li class="child"><a href="#">E-mails</a></li>
			</ul>
		</li>
		<hr>
	</ul>
</nav>
<!--
<button type="button" id="sidebarCollapse" class="btn-link pointer">
	<span></span> <span></span> <span></span>
</button>
-->
<script type="text/javascript">
	$(document).ready(function () {
		$('#sidebarCollapse').on('click', function () {
			$('#sidebar').toggleClass('active');
			$(this).toggleClass('active');
		});
	});
</script>