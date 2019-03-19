@extends('layouts.order')

@section('content')
<style type="text/css" media="screen">
	.add-to-cart span {
		/* this is the button text message */
		/* top: 0; */
		/* left: 0; */
		/* width: 100%; */
		/* height: 100%; */
	}
	.add-to-cart svg {
	/* this is the check icon */
		/* left: 50%;
		top: 50%; */
		/* width: 100%; */
		/* move the icon on the right - outside the button */
		color: transparent;
		transform: translateX(50%) translateY(-50%);
	}
	.add-to-cart.is-added span {
		/* product added to the cart - hide text message on the left with no transition*/
		color: transparent;
		transform: translateX(-100%);
	}
	.add-to-cart.is-added svg {
		/* product added to the cart - move the svg back inside the button */
		transform: translateX(-50%) translateY(-50%);
	}
</style>
	<div id="productDescApp">
		<div class="page-title pb-3">
			<h3>
				Produtos | <small class="text-muted">Descrição do produto</small>
			</h3>
		</div>
		<div class="row justify-content-center">
			<div class="col">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<img v-if="product.files.length > 0" :src="product.files[0]" class="img-fluid img-thumbnail rounded">
								<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid img-thumbnail rounded">
								
								<div class="row p-0 m-0 mt-2">
									<div class="col-sm-3 p-2" v-for="(file, index) in product.files" v-if="index < 4">
										<a :href="file" :data-fancybox="'gallery_'+product.id">
											<img v-if="product.files.length > 0" :src="product.thumbnails[index]" class="img-fluid img-thumbnail rounded">
										</a>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="row">
									<div class="col">
										<h3 class="section-title" id="prod_name">@{{ product.name | name }} <small v-if="product.category" class="text-muted">| @{{ product.category.name | name }}</small></h3>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<label class="label-plaintext label-sm" for="prod_value">Valor:</label>
										<p class="form-control-plaintext" id="prod_value">
											@{{ product.value_partner | currency(true) }} 
											@if(\App\Helpers\PermHelper::viewValues()) 
												/ @{{ product.value_seller | currency(true) }}
											@endif
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<label class="label-plaintext label-sm" for="prod_value">Medidas: <small>(P | D | AxLxC)</small></label>
										<p class="form-control-plaintext" id="prod_value">@{{ product.weight }}Kg | @{{ product.diameter }}cm | @{{ product.height }}x@{{ product.width }}x@{{ product.length }}cm</p>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<label class="label-plaintext label-sm" for="prod_description">Descrição:</label>
										<p class="form-control-plaintext" id="prod_description">@{{ product.description | default }}</p>
									</div>
								</div>
								<div class="row" v-if="product.attributes" v-for="attribute in product.attributes">
									<div class="col">
										<label class="label-plaintext label-sm" for="prod_value">@{{ attribute.name }}:</label>
										<p class="form-control-plaintext" id="prod_value">@{{ attribute.value }}</p>
									</div>
								</div>
								<div class="row">
									<div class="col pt-5">
										@if(!Auth::user())
											<div class="alert alert-light text-center" role="alert">
												<p>Para continuar com a compra é neccessário estar <a href="{{ route('login') }}">logado</a> no sistema!</p>
												<a href="{{ route('register') }}">Ainda não possui cadastro?</a>
											</div>
										@else
											<div v-if="!added">
												<!-- <a class="btn p-2 btn-primary" href="#" role="button" @click.prevent="buyItem">Comprar Agora</a> -->
												<a class="btn p-2 btn-outline-primary" href="#" role="button" @click.prevent="addItem">Adicionar ao carrinho</a>
											</div>
											<div v-else>
												<div class="alert text-center" role="alert">
													<h4 class="alert-heading">Produto adicionado ao carrinho!</h4>
													<hr>
													<a class="btn p-2 btn-success" href="{{ route('products.shop') }}" role="button">Continuar comprando</a>
													<a class="btn p-2 btn-outline-success" href="{{ route('orders.cart') }}" role="button">Ver meu carrinho</a>
												</div>
											</div>
										@endif
									</div>
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
			el: '#productDescApp',
			data: {
				product: {!! $product->toJson() !!},
				added: false
			},
			mounted: function(){
			},
			methods:{
				addItem: function(){
					let self = this;
					$.post('{{ route('orders.item.add', ['']) }}/'+self.product.id, null, function(data) {
						toastr.success('Produto adicionado ao carrinho');
						self.added = true;
					});
				}
			},
			filters: filters
		});
	</script>
@endsection
