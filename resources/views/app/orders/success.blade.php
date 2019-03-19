@extends('layouts.order')

@section('body_top')
	<div id="orderSuccessApp" class="container-fluid">
		<div class="row justify-content-md-center bg-green-l">
			<div class="col-12 col-sm-12 col-md-7 pb-4">
				<div class="col-12 pt-5 pb-3 px-sm-4 mt-3 text-white">
					<h3>
						<strong>Compra efetuada com sucesso!</strong><br>
					</h3>
					Avisaremos quando seus produtos estiverem a caminho. 
					<span v-if="order.payment_method == '{{ PAYMENT_METHOD_BILLET }}'">
						<a :href="order.payment_link" class="text-blue"><strong>Clique aqui</strong></a> para acessar o boleto do pedido.
					</span>
				</div>
				<div class="col">
					<div class="card border-0 bg-white">
						<div class="card-body">
							<div class="row align-items-center p-2" v-for="(item, index) in order.items">
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white">
										<img style="max-height: 60px" v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
										<img style="max-height: 60px" v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
									</div>
								</div>
								<div class="col pl-0">
									<p class="m-0 p-0">
										<span class="text-muted pr-3 h5">@{{ item.quantity }}x @{{ item.product.name }}</span><br>
										<span class="text-muted" v-if="item.delivery_form != 0">@{{ item.delivery_time | deadline }}</span>
										<span class="text-muted" v-else>Entraremos em contato para definir a melhor data</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class="col-12 col-sm-7 pb-4">
				<div class="col-sm-10 pt-5 pb-3 px-sm-4 mt-3 text-white">
					<a class="btn p-3 px-4 btn-primary" href="{{ $order->payment_link }}" role="button" v-if="order.payment_method == '{{ PAYMENT_METHOD_BILLET }}'"><i class="fas fa-download"></i> Download do Boleto</a>
					<a class="btn p-3 px-4 btn-primary" href="{{ route('orders.home', [$order->id]) }}" role="button">Ver detalhes do pedido</a>
					<a class="btn p-3 px-4 btn-outline-primary" href="{{ route('products.shop') }}" role="button">Voltar às compras</a>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var appDelivery = new Vue({
			el: '#orderSuccessApp',
			data: {
				order: {!! $order->toJson() !!}
			},
			filters: filters
		});

	</script>
@endsection
