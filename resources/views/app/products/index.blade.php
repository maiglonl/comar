@extends('layouts.app')

@section('content')

<div class="container">
	<div class="page-title">
		<h3>
			Produtos | <small class="text-muted">Listagem de Produtos cadastrados</small>
			<button type="button" class="btn btn-sm btn-primary float-right" title="Novo Produto" onclick="openFormProduct()"><i class="fa fa-plus"></i></button>
		</h3>
	</div>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('app.home') }}">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Produtos</li>
		</ol>
	</nav>
	<div class="row justify-content-center">
		<div class="col">
			<div class="card">
				<!--
				<div class="view overlay">
					<img class="card-img-top" src="https://mdbootstrap.com/img/Photos/Others/food.jpg" alt="Card image cap">
					<a><div class="mask rgba-white-slight"></div></a>
				</div>
				<a class="btn-floating btn-action ml-auto mr-4 mdb-color lighten-3"><i class="fa fa-chevron-right pl-1"></i></a>
				<div class="card-body">
					<h4 class="card-title">Card title</h4>
					<hr>
					<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
				</div>
				<div class="rounded-bottom mdb-color lighten-3 text-center pt-3">
					<ul class="list-unstyled list-inline font-small">
						<li class="list-inline-item pr-2 white-text"><i class="fa fa-clock-o pr-1"></i>05/10/2015</li>
						<li class="list-inline-item pr-2"><a href="#" class="white-text"><i class="fa fa-comments-o pr-1"></i>12</a></li>
						<li class="list-inline-item pr-2"><a href="#" class="white-text"><i class="fa fa-facebook pr-1"> </i>21</a></li>
						<li class="list-inline-item"><a href="#" class="white-text"><i class="fa fa-twitter pr-1"> </i>5</a></li>
					</ul>
				</div>
				-->
				<div class="card-body">
					<table id="table-products" class="table table-striped table-hover table-pointer">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Valor</th>
								<th>Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Nome</th>
								<th>Valor</th>
								<th>Status</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#table-products').DataTable({
				ajax: {
					url: "{{ route('app.products.all') }}",
					dataSrc: ""
				},
				columns: [
					{ data: "name" },
					{ data: "value" },
					{ data: "status" }
				],
				processing: true,
				order: [],
			});
		});

		function openFormProduct(){
			$.fancybox.open({
				src: '{{ route('app.products.create') }}',
				type: 'ajax',
				opts: { 
					clickOutside: false,
					clickSlide: false,
					afterClose : function(){
						$('#table-products').DataTable().ajax.reload(null, false); 
					},
				}
			});
		}
	</script>
@endsection
