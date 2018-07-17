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
		<div class="row justify-content-center">
			<div class="col">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col" style="width: 12%;"></th>
												<th scope="col" style="width: 38%;">Produto</th>
												<th scope="col" style="width: 16%;">Quantidade</th>
												<th scope="col" style="width: 17%;">Valor Unit.</th>
												<th scope="col" style="width: 17%;">Valor Total</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="item in order.items">
												<td>
													<img v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
													<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
												</td>
												<td><h4 class="mt-1">@{{ item.product.name | name}} - <small>@{{ item.product.category.name | name }}</small></h4></td>
												<td>
													<div class="input-group">
														<div class="input-group-prepend">
															<button class="btn btn-outline-secondary text-monospace" type="button" id="button-addon1">-</button>
														</div>
														<div class="form-control text-center">@{{ item.amount }}</div>
														<div class="input-group-append">
															<button class="btn btn-outline-secondary text-monospace" type="button" id="button-addon2">+</button>
														</div>
													</div>
												</td>
												<td><h4 class="mt-1">@{{ item.product.value | currency(true)}}</h4></td>
												<td><h4 class="mt-1">@{{ item.amount * item.product.value | currency(true) }}</h4></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row"><hr></div>
						<div class="row">
							<div class="col-sm-8">
								Calcular Frete:
							</div>
							<div class="col-sm-4">
								<div class="row border-top border-primary">
									<div class="col-6">Produtos</div>
									<div class="col-6 text-right">R$ 1223,00</div>
								</div>
								<div class="row border-top border-bottom border-primary">
									<div class="col-6">Frete</div>
									<div class="col-6 text-right">R$ 123,00</div>
								</div>
								<div class="row border-bottom border-primary">
									<div class="col-6">Total</div>
									<div class="col-6 text-right">R$ 1223,00</div>
								</div>
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
			},
			filters: filters
		});
	</script>
@endsection
