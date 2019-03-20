@extends('layouts.shop')

@section('body_top')
	<div class="jumbotron-fluid">
		<img src="{{ asset('images/bg_ofertas.jpg') }}" class="img-fluid">
	</div>
@endsection

@section('content')
	<style type="text/css">
		.categories-container > h3 {
			margin-top: 50px;
			margin-bottom: 50px;
			font-weight: bold;
		}
		.categories-container > h5 {
			padding-left: 30px;
		}
		.categories-container > h5.active {
			font-weight: bold;
		}
		.categories-container > h5.category-item {
			cursor: pointer;
		}
		.categories-container > h5:hover {
			font-weight: bold;
			text-decoration: none !important;
			padding-left: 33px;
		}
		.categories-container > h5 > a {
			text-decoration: none !important;
		}
		.categories-container > h5.category-item > a {
			cursor: pointer;
			color: #444b53 !important;
		}
		.loading-page{

		}
	</style>
	<div class="row" id="productShopApp">
		<div class="col-2 m-2 categories-container">
			<h4 style="padding: 30px 0px 50px 0px; "><i class="icon-menu icons h4 align-middle"></i> Categorias</h4>
			<h5 v-for="category in categories" :class="{ active : activeCategory == category.id}" class="category-item">
				<a :href="'{{ route('products.shop', ['']) }}/'+category.id">@{{ category.name }}</a>
			</h5>
			<h5 v-if="activeCategory != 0">
				<a href="{{ route('products.shop') }}" class="text-primary">Ver todos</a>
			</h5>
		</div>
		<div class="col p-4">
			<div class="row">
				<div class="col-12 col-sm-6 col-md-4 col-lg-3 pl-2 pr-2 pb-3" v-for="product in products">
					<div class="card pointer h-shadow" @click="openProductDescription(product.id)">
						<div v-if="product.files && product.files.length > 0">
							<img :src="product.thumbnails[0]" class="img-fluid border-bottom p-1">
						</div>
						<div v-else class="">
							<img src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid border-bottom p-1">
						</div>
						<div class="card-body mt_height_product" v-if="product">
								<h5 style="font-size:12px">
									&nbsp;
									<strike v-html="$options.filters.currency(product.value_partner, true)" class="text-gray" v-if="product.discount > 0"></strike>
									<span class="pl-2 text-success" v-if="product.discount > 0">@{{ product.discount }}% OFF</span>
								</h5>
							<h4 class="card-price">
								@if(\App\Helpers\PermHelper::viewValues())
									<span v-html="$options.filters.currency_sup(product.value_show)"></span>
									<span v-html="$options.filters.currency_sup(product.value_seller)" v-if="showVals"></span>
								@else
									<span v-html="$options.filters.currency_sup(product.value_show)"></span>
								@endif
							</h4>
							<h5 class="text-success" v-if="product.interest_free == 12">
								@if(\App\Helpers\PermHelper::viewValues())
									<span><i class="far fa-credit-card"></i><small> 12x <span v-html="$options.filters.currency_sup(product.value_show/12)"></span> s/ juros</small></span>
									<span v-if="showVals"><i class="far fa-credit-card"></i><small> até 12x <span v-html="$options.filters.currency_sup(product.value_seller/12)"></span> s/ juros</small><br></span>
								@else
									<i class="far fa-credit-card"></i><small> 12x <span v-html="$options.filters.currency_sup(product.value_show/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small>
								@endif
							</h5>
							<h5 v-if="product.free_shipping" class="text-success">
								<i class="fas fa-truck"></i> <small>Frete grátis</small>
							</h5>
							<p class="h6 card-text">@{{ product.name }}<br><span class="text-muted" v-if="product.category">@{{ product.category.name }}</span></p>
						</div>
					</div>
				</div>
			</div>
			@if(\App\Helpers\PermHelper::viewValues())
				<a class="btn btn-light btn-lg back-to-top text-muted toogle-value" role="button" @click.prevent="toogleView">
					<i class="far fa-eye" v-if="showVals"></i>
					<i class="far fa-eye-slash" v-else></i>
				</a>
			@endif
		</div>
	</div>
	
	<script type="text/javascript">
		new Vue({
			el: '#productShopApp',
			data: {
				activeCategory: {!! $activeCategory !!},
				categories: {!! $categories->toJson() !!},
				products: {!! $products->toJson() !!},
				showVals: false
			},
			mounted: function(){
				var self = this;
				$('.mt_height_product').matchHeight();
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
				toogleView: function(){
					this.showVals = !this.showVals;
				}
			},
			updated:function (){
				$.fn.matchHeight._update();
			},
			filters: filters
		});
	</script>
@endsection
