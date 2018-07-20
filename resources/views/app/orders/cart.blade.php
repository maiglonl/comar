@extends('layouts.order')

@section('content')
	<div id="orderCartApp" class="py-4" v-cloak>
		<div class="page-title pb-3">
			<h3>Pedidos | <small class="text-muted">Carrinho de compras</small></h3>
		</div>
		<div class="card">
			<div class="card-body ml-3 mr-3 mb-3" v-if="order && order.items && order.items.length > 0">
				<div v-for="item in order.items">
					<div class="row mt-3">
						<div class="col-1">
							<img v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
							<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
						</div>
						<div class="col-7">
							<h5 :title="item.product.description">
								<a :href="'{{ route('products.desc', ['']) }}/'+item.product.id" class="text-dark">
									<b>@{{ item.product.name | name}}</b> - <small>@{{ item.product.category.name | name }}</small>
								</a>
							</h5>
							<h5 v-if="item.product.interest_free == 12 || item.product.free_shipping">
								<span v-if="item.product.interest_free == 12" class="pr-3">
									<i class="far fa-credit-card text-success"></i>
									<small class="text-muted">Até 12 parcelas de <span v-html="$options.filters.currency_sup(item.product.{{ \App\Helpers\PermHelper::lowerValueText() }}/12)"></span> s/ juros</small>
								</span>
								<span v-if="item.product.free_shipping">
									<i class="fas fa-truck text-success"></i> 
									<small class="text-muted">Frete grátis</small>
								</span>
							</h5>
							<div class="row">
								<div class="col-12 mt-3">
									<a href="#">Comprar agora</a>
									<span class="px-2 text-muted">|</span>
									<a href="#">Salvar para depois</a>
									<span class="px-2 text-muted">|</span>
									<a href="#" @click.prevent="removeItem(item.id)">Excluir</a>
								</div>
							</div>
						</div>
						<div class="col-2">
							<div class="input-group mt-2">
								<div class="input-group-prepend">
									<button class="btn btn-outline-secondary text-monospace" type="button" id="button-addon1" @click.prevent="decreaseItem(item.id)" :disabled="item.amount<=1">-</button>
								</div>
								<div class="form-control text-center">@{{ item.amount }}</div>
								<div class="input-group-append">
									<button class="btn btn-outline-secondary text-monospace" type="button" id="button-addon2" @click.prevent="increaseItem(item.id)">+</button>
								</div>
							</div>
						</div>
						<div class="col-2 text-right">
							<h3 class="mt-2 font-weight-light" v-html="$options.filters.currency_sup(item.amount * item.product.{{ \App\Helpers\PermHelper::lowerValueText() }})"></h3>
						</div>
					</div>
					<div class="row">
						<div class="col-12 pt-1">
							<hr>
						</div>
					</div>
				</div>
				<div class="row text-right mt-4">
					<div class="col">
						<a href="{{ route('orders.delivery') }}" class="btn btn-primary">Finalizar Compra</a>
					</div>
				</div>
			</div>
			<div class="card-body ml-3 mr-3 mb-3" v-else>
				<div class="row">
					<div class="col text-center">
						<h3>Você ainda não tem ítens no carrinho!</h3>
					</div>
				</div>
				<div class="row text-center mt-4">
					<div class="col">
						<a href="{{ route('products.shop') }}" class="btn btn-primary">Volte às compras</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#orderCartApp',
			data: {
				order: {!! $order->toJson() !!}
			},
			mounted: function(){
			},
			methods:{
				reloadData: function (){
					var self = this;
					$.get('{{ route('orders.find', [$order->id]) }}', function(data) {
						if(data.error){
							toastr.error('Falha ao atualizar pedido!');
						}else{
							self.order = data;
						}
					});
				},
				removeItem: function (id){
					var self = this;
					$.delete('{{ route('items.destroy', ['']) }}/'+id, null, function(data) {
						self.reloadData();
					});
				},
				increaseItem: function (id){
					var self = this;
					$.post('{{ route('items.increase', ['']) }}/'+id, null, function(data) {
						self.reloadData();
					});
				},
				decreaseItem: function (id){
					var self = this;
					$.post('{{ route('items.decrease', ['']) }}/'+id, null, function(data) {
						self.reloadData();
					});
				}
			},
			filters: filters
		});
	</script>
@endsection
