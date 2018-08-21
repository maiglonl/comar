@extends('layouts.order')

@section('pre-scripts')
	<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
@endsection

@section('body_top')
	<div id="orderCardCreateApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-12 col-sm-8 pb-4">
				<div class="pt-5 pb-3 px-sm-4 mt-3">
					<h4 class="pb-4">Opções de pagamento</h4>
					<div class="card mb-4 border-0 bg-gray-50">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
								</div>
								<div class="col pl-0">
									<p class="m-0 p-0">
										<strong>Cartão de Crédito</strong><br>
									</p>
								</div>
								<div class="col-sm-4 col-12 text-sm-right text-center">
									<a href="{{ route('orders.payment') }}" class="btn btn-link">Alterar</a>
								</div>
							</div>
						</div>
					</div>
					<h4 class="pb-2 pt-4">Cadastre um novo cartão</h4>
					<div class="row pt-2">
						<div class="col">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-sm">
											<form action="{{ route('cards.store') }}" id="formCreateCard" data-prefix="cc">
												@include('app.cards._form', ['formOnly' => true])
											</form>
										</div>
										<div class="col-sm">
											<img src="{{ asset("images/credit_card.png")}}" class="img-fluid">
										</div>
									</div>
								</div>
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
			el: '#orderCardCreateApp',
			data: {
				card: {
					number: null,
					name: null,
					date_due: null,
					cvv: null,
					hash: null,
					brand: null,
					user_id: '{{ Auth::id() }}'
				},
				order: {!! $order->toJson() !!}
			},
			methods: {
				submitCardForm: function(){
					let self = this;
					PagSeguroDirectPayment.getBrand({
						cardBin: self.card.number.substr(0, 6),
						success: function(response) {
							self.card.brand = response.brand.name;
							PagSeguroDirectPayment.createCardToken({
								cardNumber: self.card.number,
								brand: self.card.brand,
								cvv: self.card.cvv,
								expirationMonth: self.card.date_due.substr(0,2),
								expirationYear: '20'+self.card.date_due.substr(3,2),
								success: function(response){
									self.card.hash = response.card.token;
									$("#formCreateCard").submit();
								},
								error: function(response){
									toastr.error('Falha ao registrar cartão!');
								}
							});
						},
						error: function(response) {
							toastr.error('Falha ao registrar cartão!');
						}
					});
				}
			},
			mounted: function(){
				var self = this;
				PagSeguroDirectPayment.setSessionId('{{ $order->session }}');
				$("#formCreateCard").cValidate({
					data: self.card,
					success: function(result){
						$.post('{{ route('orders.payment.select_card') }}', { 'card_id': result.data.id }).then(function(){
							location.href = "{{ route('orders.payment.card') }}";
						});
					}
				});
			},
			filters: filters
		});
	</script>
@endsection
