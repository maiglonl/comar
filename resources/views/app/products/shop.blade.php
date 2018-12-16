@extends('layouts.order')

@section('body_top')
	<div class="jumbotron-fluid">
		<img src="{{ asset('images/bg_ofertas.jpg') }}" class="img-fluid">
	</div>
@endsection

@section('content')
	
	<div class="row" id="productShopApp">
		<div class="col-sm-12 p-4">
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
							<h4 class="card-price">
								@if(\App\Helpers\PermHelper::viewValues())
									<span v-html="$options.filters.currency_sup(product.value_seller)" v-if="showVals"></span>
									<span v-html="$options.filters.currency_sup(product.value_partner)"></span>
								@else
									<span v-html="$options.filters.currency_sup(product.value_partner)"></span>
								@endif
							</h4>
							<h5 :class="[ product.interest_free == 12 ? 'text-success' : 'text-muted' ]">
								@if(\App\Helpers\PermHelper::viewValues())
									<span v-if="showVals"><i class="far fa-credit-card"></i><small> 12x <span v-html="$options.filters.currency_sup(product.value_seller/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small><br></span>
									<span><i class="far fa-credit-card"></i><small> 12x <span v-html="$options.filters.currency_sup(product.value_partner/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small></span>
								@else
									<i class="far fa-credit-card"></i><small> 12x <span v-html="$options.filters.currency_sup(product.value_partner/12)"></span><span v-if="product.interest_free == 12"> s/ juros</span></small>
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
