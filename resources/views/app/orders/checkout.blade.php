@extends('layouts.app')

@section('content')
	<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
	<div id="orderCartApp">
		<div class="page-title">
			<h3>
				Pedidos | <small class="text-muted">Checkout</small>
			</h3>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('products.index') }}">Pedidos</a></li>
				<li class="breadcrumb-item active" aria-current="page">Checkout</li>
			</ol>
		</nav>
		<div class="row justify-content-center">
			<div class="col">
				<div class="card">
					<div class="card-body">
						<form action="" id="formOrderCheckout">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<button type="submit" class="btn btn-success float-right" @click.prevent="submitFormCheckout" title="Salvar">{!! ICONS_OK !!}</i></button>
										<button type="button" class="btn btn-danger float-left" @click.prevent="cancelFormCheckout" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		const paymentData = {
			brand: ''
		}
		$(document).ready(function() {
			$("#cardNumber").on('keyup', function(){
				if($(this).length >= 6) {
					let bin = $(this).val();
				}
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
						let cardNumber = "1234123412341234";
						let cvv = "123";
						let cardName = "ABCDEF GHIJKL";
						let expirationMonth = "01";
						let expirationYear = "2020";
						let brand = "VISA";
						$.post('{{ route('orders.checkout.post') }}', { order: self.order }, function(data) {	
							if(data.error){
								toastr.error('Falha ao finalizar compra!');
							}else{
								toastr.success('Compra realizada com sucesso');
							}
						});
					});
					$("#formOrderCheckout").submit();
				}
			},
			filters: filters
		});
	</script>
@endsection
