@extends('layouts.order')

@section('body_top')
	<div id="orderDeliveryApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-xs-11 col-sm-7">
				<div class="pt-5 pb-3 px-4 mt-3">
					<h4 class="pb-4">Opções de pagamento</h4>
					<div class="col rounded bg-gray-50 p-3">
						<div class="row py-2 align-items-center">
							<div class="col-auto px-2 mx-3 text-center">
								<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
							</div>
							<div class="col pl-0">
								<p class="m-0 p-0">
									<strong>Cartão de crédito</strong><br>
									<span class="text-success">Em até 12 parcelas sem juros</span>
								</p>
							</div>
							<div class="col-sm-4 col-xs-12 text-sm-right text-xs-center">
								<a href="{{ route('orders.payment') }}" class="pr-3">Alterar</a>
							</div>
						</div>
					</div>
					<div class="row pt-4">
						<div class="col">
							<p>Outros meios</p>
							<div class="card shadow-sm">
								<ul class="list-group list-group-flush">
									<li class="list-group-item hover-item">
										<div class="row py-2 align-items-center">
											<div class="col-auto px-4 text-center">
												<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
											</div>
											<div class="col pl-0">
												<p class="m-0 p-0">
													<strong>Cartão de crédito</strong><br>
													<span class="text-success">Em até 12 parcelas sem juros</span>
												</p>
											</div>
										</div>
									</li>
									<li class="list-group-item hover-item">
										<div class="row py-2 align-items-center">
											<div class="col-auto px-4 text-center">
												<div class="text-primary rounded bg-white"><i class="fas fa-barcode fa-2x rounded px-3 py-2 border border-primary"></i></div>
											</div>
											<div class="col pl-0">
												<h5 class="m-0 p-0">Boleto</h5>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>	
					</div>
					<div class="col rounded bg-gray-50 mt-5">
						<div class="row py-2 align-items-center">
							<div class="col text-right">
								<h4 class="mb-0 py-2">Custo de envio <span class="text-muted pl-3">R$123,12</span></h4>
							</div>
						</div>
					</div>
				</div>
				<div class="col px-4 text-right">
					<a href="{{ route('orders.checkout') }}" class="btn btn-primary">Continuar</a>
				</div>
			</div>
			<div class="col-xs-11 col-sm-3 bg-gray-50 vh-100">
				<div class="py-5 px-4 mt-3">
					<p class="mb-2"><b>Resumo da Compra</b></p>
					<hr class="mt-0 mb-4">
					<p class="mb-2">Produtos(X) <span class="float-right">R$123,12</span></p>
					<p>Envio <span class="float-right">R$123,12</span></p>
					<hr class="my-4">
					<p>Total <span class="float-right">R$123,12</span></p>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function() {
			
		});
		new Vue({
			el: '#orderDeliveryApp',
			data: {
				order: {!! $order->toJson() !!},
				user: {!! Auth::user()->toJson() !!}
			},
			mounted: function(){
			},
			methods:{
				changeAdress: function(){
					swal("Alterar dados de entrega");
				}
			},
			filters: filters
		});
	</script>
@endsection
