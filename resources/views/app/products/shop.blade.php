@extends('layouts.app')

@section('content')
	<div class="page-title">
		<h3>
			Produtos | <small class="text-muted">Listagem de Produtos cadastrados</small>
			<button type="button" class="btn btn-sm btn-primary float-right" title="Novo Produto" onclick="openFormProduct()">{!! ICONS_ADD !!}</button>
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
				<div class="card-body">
					<div class="row">
						<div class="col-3" v-for="product in products">
							<div class="card">
								<img class="card-img-top" src="">
								<div class="card-body">
									<h5 class="card-title">Card title</h5>
									<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Cras justo odio</li>
									<li class="list-group-item">Dapibus ac facilisis in</li>
									<li class="list-group-item">Vestibulum at eros</li>
								</ul>
								<div class="card-body">
									<a href="#" class="card-link">Card link</a>
									<a href="#" class="card-link">Another link</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		<script type="text/javascript">
		new Vue({
			el: '#productShopApp',
			data: {
				products: {!! $products->toJson() !!}
			},
			mounted: function(){
				var self = this;
			},
			methods:{
				reloadData: function (){
					var self = this;
					$.get('{{ route('app.products.find', [$product->id]) }}', function(data) {
						if(data.error){
							toastr.error('Falha ao atualizar produto!');
						}else{
							self.product = data;
						}
					});
				}
			},
			filters: filters
		});
	</script>
	</script>
@endsection
