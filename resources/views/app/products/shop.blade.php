@extends('layouts.app')

@section('content')
	<div class="page-title">
		<h3>
			Produtos | <small class="text-muted">Listagem de Produtos disponíveis</small>
		</h3>
	</div>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('app.home') }}">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Comprar</li>
		</ol>
	</nav>
	<div class="row justify-content-center" id="productShopApp">
		<div class="col">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-3" v-for="product in products">
							<div class="card mb-4">
								<div v-if="product.files.length > 0" class="bs_hovereffect rounded">
									<img :src="product.thumbnails[0]" class="img-fluid img-thumbnail rounded">
									<div class="overlay">
										<div class="text-center">
											<p class="bs_group0">
												<a :href="product.files[0]" :data-fancybox="'gallery_'+product.id" title="Visualizar Imagem">
													{!! ICONS_ADD !!}
												</a>
												<img v-for="file in product.files" :src="file" :data-fancybox="'gallery_'+product.id" style="display: none">
											</p>
										</div>
									</div>
								</div>
								<div v-else class="bs_hovereffect rounded">
									<img src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid img-thumbnail rounded">
								</div>
								<div class="card-body">
									<h5 class="card-title mt_height_name"><a href="qwe">@{{ product.name }}</a> <br> <small>@{{ product.category.name }}</small></h5>
									<p class="card-text mt_height_description">@{{ product.description | limit_words(15) }}</p>
								</div>
								<ul class="list-group list-group-flush">
									@if(Auth::user()->role == USER_ROLES_ADMIN)
										<h2><li class="list-group-item"><span>R$</span><span class="float-right">@{{ product.value_seller | currency }}</span></li></h2>
										<h2><li class="list-group-item"><span>R$</span><span class="float-right">@{{ product.value_partner | currency }}</span></li></h2>
									@elseif(Auth::user()->role == USER_ROLES_SELLER)
										<h2><li class="list-group-item"><span>R$</span><span class="float-right">@{{ product.value_seller | currency }}</span></li></h2>
									@else
										<h2><li class="list-group-item"><span>R$</span><span class="float-right">@{{ product.value_partner | currency }}</span></li></h2>
									@endif
								</ul>
								<div class="card-footer-btn btn btn-primary">
									Comprar
								</div>
							</div>
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
					$.get('{{ route('app.products.all') }}', function(data) {
						if(data.error){
							toastr.error('Falha ao carregar produtos!');
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
