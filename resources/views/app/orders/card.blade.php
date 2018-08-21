@extends('layouts.order')

@section('pre-scripts')
	<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
@endsection

@section('body_top')
	<div id="orderCardApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-12 col-sm-8 pb-4">
				<div class="pt-5 pb-3 px-sm-4 mt-3">
					<h4 class="pb-4">Opções de pagamento</h4>
					<div class="card mb-4 border-0 bg-gray-50" v-if="order.card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
								</div>
								<div class="col pl-0">
									<p class="m-0 p-0">
										<strong>Cartão de Crédito @{{ order.card.brand | name }} com final @{{ order.card.number.substr(12,4) }}</strong><br>
										<span class="text-success">Em até 12 parcelas</span>
									</p>
								</div>
								<div class="col-sm-4 col-12 text-sm-right text-center">
									<a href="{{ route('orders.payment') }}" class="btn btn-link">Alterar</a>
								</div>
							</div>
						</div>
					</div>
					<h4 class="pb-2 pt-4">Opções de parcelamento</h4>
					<div class="row pt-2">
						<div class="col">
							<div class="card closed-card">
								<div class="card-header pointer" onclick="toogleHeader(this)">
									<h5 class="card-title py-4 m-0">Modificar envio <span class="float-right"><i class="icon-arrow-up icons"></i></span></h5>
								</div>
								<ul class="list-group list-group-flush">
									<span v-for="method in group.delivery_availables" 
										:data-method="method.codigo" 
										:data-group="index"
										:class="[method.codigo == group.delivery_form ? 'selected-item' : 'unselected-item']"
										onclick="toogleItemHandler(this)">
										<li class="list-group-item align-items-center" style="cursor:pointer;">
											<div class="row align-items-center p-2">
												<div class="col text-center text-md-left">
													<h5 class="m-0 p-0"><b>@{{ method.codigo | delivery_form }}</b></h5>
													<span class="text-muted" v-if="method.codigo != 0">@{{ method.prazo | deadline }}</span>
													<span class="text-muted" v-else>Entraremos em contato para definir a melhor data</span>
												</div>
												<div class="col-md-3 mt-3 mt-sm-0">
													<h5 class="item-price text-center text-md-right m-0 p-0">
														<span v-html="$options.filters.currency_sup(method.valor, true)" v-if="method.valor > 0"></span>
														<span class="free-text"	v-else>Grátis</span>
														<span class="item-icon-arrow float-right text-primary pl-3"><i class="icon-arrow-down icons"></i></span>
													</h5>
												</div>
											</div>
										</li>
									</span>
								</ul>
								<div class="card-body">
									<div class="row align-items-center" v-for="item in group.items">
										<div class="col-auto text-center" style="width: 70px">
											<img v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
											<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
										</div>
										<div class="col pl-0">
											<p class="m-0 p-0">
												<span class="text-muted">@{{ item.product.name }}</span><br>
												<span class="text-muted">Quantidade: @{{ item.amount }}</span>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col rounded bg-gray-50 mt-5">
						<div class="row py-2 align-items-center">
							<div class="col text-right">
								<h4 class="mb-0 py-2">Custo de envio 
									<span class="text-muted pl-3" v-if="order.total_delivery > 0" v-html="$options.filters.currency_sup(order.total_delivery, true)"></span>
									<span class="text-success pl-3" v-else>Grátis</span>
								</h4>
							</div>
						</div>
					</div>
				</div>
				<div class="px-sm-4 text-right">
					<button type="button" @click.prevent="submitCardForm" class="btn btn-primary">Continuar</button>
				</div>
			</div>
			@include('app.orders._resume')
		</div>
	</div>
	
	<script type="text/javascript">
		if(performance.navigation.type == 2){
		   location.reload(true);
		}
		var appCard = new Vue({
			el: '#orderCardApp',
			data: {
				order: {!! $order->toJson() !!}
			},
			methods: {
				
			},
			mounted: function(){
				var self = this;
				PagSeguroDirectPayment.setSessionId('{{ $order->session }}');

				$("#formCreateCard").cValidate({
					data: self.card,
					success: function(result){
						$.post('{{ route('orders.payment.select_card') }}', { card_id: result.id }).then(function(){
							location.href = "{{route('orders.checkout')}}";
						});
					}
				});
			},
			filters: filters
		});
	</script>
@endsection
