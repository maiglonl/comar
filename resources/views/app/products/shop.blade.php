@extends('layouts.app')

@section('content')
	<div class="page-title">
		<h3>
			Produtos | <small class="text-muted">Listagem de Produtos disponíveis</small>
		</h3>
	</div>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Comprar</li>
		</ol>
	</nav>
	<div class="row">
		<div class="col-sm-5 col-md-4 col-lg-3">
			<div class="content">
				<h4>Pesquisa de produtos</h4>
				<p>xxx resultados</p>
			</div>
			<div class="content">
				<h5>Ordenar resultados</h5>
				<p>Mais relevante V | X Y</p>
			</div>
			<div class="content">
				<h5>Categorias</h5>
				<p>
					Categoria a (123)<br>
					Categoria b (123)<br>
					Categoria c (123)<br>
					Categoria d (123)<br>
					Categoria e (123)<br>
					Categoria f (123)<br>
					Categoria g (123)<br>
				</p>
			</div>
			<div class="content">
				<h5>Preço</h5>
				<p>De R$[___] até R$[___]</p>
			</div>

		</div>
		<div class="col-sm-7 col-md-8 col-lg-9" id="productShopApp">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-4 pl-2 pr-2 pb-3" v-for="product in products">
					<div class="card pointer h-shadow" @click="openProductDescription(product.id)">
						<div v-if="product.files.length > 0">
							<img :src="product.thumbnails[0]" class="img-fluid border-bottom p-1">
						</div>
						<div v-else class="">
							<img src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid border-bottom p-1">
						</div>
						<div class="card-body">
							<h4 class="card-price">
								@if(Auth::user() && Auth::user()->role == USER_ROLES_ADMIN)
									<span v-html="currency_sup(product.value_seller)"></span>
									<span v-html="currency_sup(product.value_partner)"></span>
								@elseif(Auth::user() && Auth::user()->role == USER_ROLES_SELLER)
									<span v-html="currency_sup(product.value_seller)"></span>
								@else
									<span v-html="currency_sup(product.value_partner)"></span>
								@endif
							</h4>
							<h5 :class="[ product.interest_free == 12 ? 'text-success' : 'text-muted' ]">
								@if(Auth::user() && Auth::user()->role == USER_ROLES_ADMIN)
									<i class="far fa-credit-card"></i><small>12x <span v-html="currency_sup(product.value_seller/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small><br>
									<i class="far fa-credit-card"></i><small>12x <span v-html="currency_sup(product.value_partner/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small>
								@elseif(Auth::user() && Auth::user()->role == USER_ROLES_SELLER)
									<i class="far fa-credit-card"></i><small>12x <span v-html="currency_sup(product.value_seller/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small>
								@else
									<i class="far fa-credit-card"></i><small>12x <span v-html="currency_sup(product.value_partner/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small>
								@endif
							</h5>
							<h5 :class="[ product.free_shipping ? 'text-success' : 'text-muted' ]">
								<i class="fas fa-truck"></i> 
								<small v-if="product.free_shipping">Frete grátis</small>
								<small v-else>Entrega p/ todo o Brasil</small>
							</h5>
							<h5 class="card-text mt_height_name">@{{ product.name }} - <small>@{{ product.category.name }}</small></h5>
							<h5></h5>
							<p class="mt_height_description">@{{ product.description | limit_words(12) }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		new Vue({
			el: '#productShopApp',
			data: {
				products: {!! $products->toJson() !!}
			},
			mounted: function(){
				var self = this;
				$('.mt_height_name').matchHeight();
				$('.mt_height_description').matchHeight();
			},
			methods:{
				reloadData: function (){
					var self = this;
					$.get('{{ route('products.all') }}', function(data) {
						if(data.error){
							toastr.error('Falha ao carregar produtos!');
						}else{
							self.product = data;
						}
					});
				},
				openProductDescription: function (id){
					location.href = '{{ route('products.desc', ['']) }}/'+id;
				},
				currency_sup: function (val){
					return filters.currency_sup(val, true);
				}
			},
			filters: filters
		});
	</script>
@endsection
