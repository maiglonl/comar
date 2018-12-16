@extends('layouts.order')

@section('pre-scripts')
	<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
@endsection

@section('body_top')
	<div id="orderDeliveryApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-12 col-sm-8 pb-4">
				<div class="pt-5 pb-3 px-sm-4 mt-3">
					<h4 class="pb-4">Revise e confirme sua compra</h4>
					<p>Produtos e prazos</p>
					<div class="card border-0 bg-gray-50" v-for="(item, index) in order.items">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white">
										<img style="max-height: 60px" v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
										<img style="max-height: 60px" v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
									</div>
								</div>
								<div class="col pl-0">
									<p class="m-0 p-0">
										<strong>@{{ item.product.name }}</strong><br>
										<span class="text-muted pr-3">Quantidade: @{{ item.quantity }}</span>
										<span class="text-muted">Prazo: 
											<span class="text-muted" v-if="item.delivery_form != 0">@{{ item.delivery_time | deadline }}</span>
											<span class="text-muted" v-else>Entraremos em contato para definir a melhor data</span>
										</span>
									</p>
								</div>
							</div>
						</div>
					</div>
					<p class="mt-4">Local de entrega</p>
					<div class="card border-0 bg-gray-50 mb-4">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white">
										<i class="fas fa-map-marker-alt fa-2x rounded px-3 py-2 border border-primary"></i>
									</div>
								</div>
								<div class="col pl-0">
									<p class="m-0 p-0">
										<strong>@{{ order.street }}, @{{ order.number }}</strong><br>
										<span class="text-muted"><span v-if="order.complement">@{{ order.complement }}, </span>@{{ order.district }} - @{{ order.city }}/@{{ order.state }}</span>
									</p>
								</div>
								<div class="col-sm-4 col-12 text-sm-right text-center">
									<a href="{{ route('orders.delivery') }}" class="btn btn-link">Alterar entrega</a>
								</div>
							</div>
						</div>
					</div>
					<p class="mt-4">Forma e dados de pagamento</p>
					<div class="card border-0 bg-gray-50 mb-4">
						<div class="card-body">
							<div class="row align-items-center" v-if="order.payment_method == '{{ PAYMENT_METHOD_CREDIT_CARD }}'">
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
								</div>
								<div class="col pl-0">
									<p class="m-0 p-0">
										<strong>Cartão de Crédito @{{ order.card.brand | name }} com final @{{ order.card.number.substr(12,4) }}</strong>
									</p>
								</div>
								<div class="col-sm-4 col-12 text-sm-right text-center">
									<a href="{{ route('orders.payment') }}" class="btn btn-link">Alterar pagamento</a>
								</div>
							</div>
							<div class="row align-items-center" v-else>
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white"><i class="fas fa-barcode fa-2x rounded px-3 py-2 border border-primary"></i></div>
								</div>
								<div class="col pl-0"><p class="m-0 p-0"><strong>Boleto</strong></p></div>
								<div class="col-sm-4 col-12 text-sm-right text-center">
									<a href="{{ route('orders.payment') }}" class="btn btn-link">Alterar pagamento</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@include('app.orders._confirm')
		</div>
	</div>
	
	<script type="text/javascript">
		if(performance.navigation.type == 2){
		   location.reload(true);
		}
		var appDelivery = new Vue({
			el: '#orderDeliveryApp',
			data: {
				order: {!! $order->toJson() !!},
				user: {!! Auth::user()->toJson() !!},
				senderHash: "",
				total: 0
			},
			mounted: function(){
				let self = this;
				PagSeguroDirectPayment.setSessionId('{!! $order->session !!}');
				if(self.order.payment_method == '{{ PAYMENT_METHOD_CREDIT_CARD }}'){
					self.loadGroups();
				}
				self.reloadTotal();
			},
			methods:{
				confirmBuy: function (){
					var self = this;
					pagSeguro.getSenderHash().then(function(data){
						self.senderHash = data ? data : "";
						let postData = { 
							senderHash: self.senderHash,
							installments: self.order.payment_groups,
							token: ''
						};
						let postCallback = function(data) {
							if(data.error){
								toastr.error('Falha ao realizar operação!');
							}else{
								location.href = '{{ route('orders.checkout.success', [$order->id])}}';
							}
						};
						if(self.order.payment_method == '{{ PAYMENT_METHOD_CREDIT_CARD }}'){
							PagSeguroDirectPayment.createCardToken({
								cardNumber: self.order.card.number,
								brand: self.order.card.brand,
								cvv: self.order.card.cvv,
								expirationMonth: self.order.card.date_due.substr(0,2),
								expirationYear: '20'+self.order.card.date_due.substr(3,2),
								success: function(response){
									postData.token = response.card.token;
									$.post('{{ route('orders.checkout') }}', postData , postCallback);
								},
								error: function(response){
									toastr.error('Falha na validação do cartão!');
								}
							});
						}else{
							$.post('{{ route('orders.checkout') }}', postData , postCallback);
						}
						
						
					});
				},
				loadGroups: function(){
					let self = this;
					$.each(self.order.payment_groups, function(index, group) {
						let max = index.split('_')[1];
						if(group.items.length > 0){
							self.setGroupInstallments(max, group.total);
						}
					});
				},
				setGroupInstallments: function(interest_free, value){
					let self = this;
					PagSeguroDirectPayment.getInstallments({	
						amount: parseFloat(value),
						brand: self.order.card.brand,
						maxInstallmentNoInterest: parseInt(interest_free),
						success: function(response) {
							if(response.error){
								toastr.error('Falha ao carregar parcelamento!');
							}
							let installments = response.installments[Object.keys(response.installments)[0]];
							let count = self.countInstallmentsFreeInterest(installments);
							self.order.payment_groups['free_'+count].installments = installments;
							self.reloadTotal();
						},
						error: function(response) {
							if(response.error){
								toastr.error('Falha ao carregar parcelamento!');
							}
						}
					});
				},
				countInstallmentsFreeInterest: function(installments){
					let result = 0;
					$.each(installments, function(index, installment){
						result += installment.interestFree ? 1 : 0;
					})
					return result;
				},
				reloadTotal: function(){
					let self = this;
					let result = parseFloat(0);
					$.each(self.order.payment_groups, function(index, group){
						if(group.installments.length >= group.selected-1){
							result += parseFloat(group.installments[group.selected-1].totalAmount);
						}else{
							result += parseFloat(group.total);
						}
					});
					self.total = result;
				}
			},
			filters: filters
		});

	</script>
@endsection
