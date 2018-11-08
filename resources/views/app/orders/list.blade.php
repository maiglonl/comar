@extends('layouts.app')

@section('content')
	<div class="container-fluid" id="appOrdersList">
		<div class="row">
			<div class="col">
				<div class="page-title">
					<h3>
						Compras | <small>Pedidos realizados</small>
						<!--
							<button type="button" class="btn btn-primary float-right" title="Adicionar novo Parceiro">
								<i class="fas fa-plus"></i>
							</button>
						-->
					</h3>
				</div>
				<nav class="nav nav-tabs nav-fill nav-inside-tabs">
					<a class="nav-item nav-link py-3 active" data-toggle="tab" href="#openTabContent" title="Pedidos em andamento">Em andamento</a>
					<a class="nav-item nav-link py-3" data-toggle="tab" href="#endedTabContent" title="Pedidos finalizados">Finalizados</a>
					<a class="nav-item nav-link py-3" data-toggle="tab" href="#allTabContent" title="Todos os pedidos">Todos</a>
				</nav>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="openTabContent">
						<div class="card p-4 m-4" v-for="order in orders">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="row">
										<div class="col-sm-12">
											<h5 :class="getStatusClass(order)"><b>@{{ order.status.name }}</b></h5>
											<label>Número: @{{ order.id }}</label>
											<br>
											<label>Data: @{{ order.created_at | datetime }}</label>
											<br>
											<a :href="'{{ route('orders.home', ['']) }}/'+order.id">Mais detalhes</a>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-8 border-md-left text-right">
									<div class="row align-items-center" v-for="item in order.items">
										<div class="col pl-0">
											<p class="m-0 p-0">
												<span class="text-muted">@{{ item.quantity }} x @{{ item.product.name }}</span><br>
											</p>
										</div>
										<div class="col-auto text-center" style="width: 70px">
											<img v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
											<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="endedTabContent">
					</div>
					<div class="tab-pane fade" id="allTabContent">
					</div>
				</div>
				<div class="card">
					
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#appOrdersList',
			data: {
				orders: {!! $orders->toJson() !!}
			},
			mounted: function(){
			},
			methods:{
				getStatusClass: function(order){
					switch(parseInt(order.status.id)){
						case {{ STATUS_ORDER_CANCELADO }}: return "text-danger"; break;
						case {{ STATUS_ORDER_ETREGUE }}: return "text-success"; break;
						default: return "text-primary"; break;
					}
				}
			},
			filters: filters
		});
	</script>
@endsection
