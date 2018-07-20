@extends('layouts.app')

@section('content')
	<div id="orderCartApp" v-cloak>
		<div class="page-title">
			<h3>
				Pedidos | <small class="text-muted">Pedido em andamento</small>
			</h3>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('products.index') }}">Pedidos</a></li>
				<li class="breadcrumb-item active" aria-current="page">Novo pedido</li>
			</ol>
		</nav>
		<div class="card">
			<div class="card-body ml-3 mr-3 mb-3">
				<div class="row mt-3" v-for="item in order.items">
					<div class="col-1">
						<img v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
						<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
					</div>
					<div class="col-7">
						<h5 :title="item.product.description">@{{ item.product.name | name}} - <small>@{{ item.product.category.name | name }}</small></h5>
						<h5>
							<span v-if="item.product.interest_free == 12" class="pr-3">
								<i class="far fa-credit-card text-success"></i>
								<small class="text-muted">Até 12 parcelas de <span v-html="currency_sup(item.product.{{ \App\Helpers\PermHelper::lowerValueText() }}/12)"></span> s/ juros</small>
							</span>
							<span>
								<i class="fas fa-truck" :class="[ item.product.free_shipping ? 'text-success' : 'text-muted' ]"></i> 
								<small v-if="item.product.free_shipping" class="text-muted">Frete grátis</small>
							</span>
						</h5>
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
						<h3 class="mt-2 font-weight-light" v-html="currency_sup(item.amount * item.product.{{ \App\Helpers\PermHelper::lowerValueText() }})"></h3>
					</div>
					<div class="col-12 pt-3">
						<hr>
					</div>
				</div>
				<div class="row text-right mt-4">
					<div class="col">
						<a href="{{ route('orders.checkout', [$order->id]) }}" class="btn btn-primary">Finalizar Compra</a>
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
				},
				currency_sup: function (val){
					return filters.currency_sup(val, true);
				}
			},
			filters: filters
		});
	</script>
@endsection
