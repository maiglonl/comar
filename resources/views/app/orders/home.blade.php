@extends('layouts.app')

@section('content')
	<div class="container-fluid p-0 bg-white" id="appOrdersList">
		<div class="page-title">
			<h3>Compras | <small>Pedidos realizados</small></h3>
		</div>
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
