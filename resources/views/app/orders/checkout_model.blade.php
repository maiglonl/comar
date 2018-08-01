@extends('layouts.order')

@section('pre-scripts')
	<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
@endsection

@section('content')
	<div id="orderCartApp" class="py-4">
		<div class="page-title pb-3">
			<h3>
				Pedidos | <small class="text-muted">Checkout</small>
			</h3>
		</div>
		<div class="row justify-content-center">
			<div class="col">
				<div class="card">
					<div class="card-body">
						<form action="" id="formOrderCheckout">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<input type="text" id="cardNumber">
										<button type="submit" class="btn btn-success float-right" @click.prevent="submitFormCheckout" title="Salvar">{!! ICONS_OK !!}</i></button>
										<button type="button" class="btn btn-danger float-left" @click.prevent="cancelFormCheckout" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
										<input type="text" id="senderName">
									</div>
								</div>
							</div>
						</form>
						<div id="brands"></div>
						<div id="installments"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		const paymentData = {
			brand: '',
			amount: {{ $amount }},
		}
		PagSeguroDirectPayment.setSessionId('{!! $session !!}');
		pagSeguro.getPaymentMethods(paymentData.amount).then(function(urls){
			let html = '';
			urls.forEach(function(url){
				html += '<img src="'+url+'" class="credit_card">';
			});
			$("#brands").html(html);
		});
		$(document).ready(function() {
			$("#cardNumber").on('keyup', function(){
				if($(this).val().length >= 6) {
					let bin = $(this).val();
					pagSeguro.getBrand(bin).then(function(res){
						paymentData.brand = res.result.brand.name;
						return pagSeguro.getInstallments(paymentData.amount, paymentData.brand);
					}).then(function(res){
						console.log(res);
					});
				}
			});
			$("#senderName").on('change', function(){
				pagSeguro.getSenderHash().then(function(data){
					console.log(data);
				});
			});
		});
		new Vue({
			el: '#orderCartApp',
			data: {
				order: {!! $order->toJson() !!},
				user: {!! Auth::user()->toJson() !!}
			},
			mounted: function(){
			},
			methods:{
				cancelFormCheckout: function (){ 
					swal('error', 'Pedido cancelado!');
				},
				submitFormCheckout: function (){ 
					var self = this;
					validaForm("#formOrderCheckout", function(){
						let params = {
							cardNumber: "5472121389478241",
							cvv: "164",
							expirationMonth: "01",
							expirationYear: "2019",
							brand: paymentData.brand
						};
						pagSeguro.createCardToken(params).then(function(token){
							console.log(token);
							$.post('{{ route('orders.checkout.post') }}', { order: self.order }, function(data) {	
								if(data.error){
									toastr.error('Falha ao finalizar compra!');
								}else{
									toastr.success('Compra realizada com sucesso');
								}
							});
						});
					});
					$("#formOrderCheckout").submit();
				}
			},
			filters: filters
		});
	</script>
@endsection
