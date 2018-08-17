@extends('layouts.order')

@section('body_top')
	<div id="orderPaymentApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-12 col-sm-8 pb-4">
				<div class="pt-5 pb-3 px-sm-4 mt-3">
					<h4 class="pb-4">Opções de pagamento</h4>
					<div class="row pt-4" v-if="">
						<div class="col">
							<p>Cartões registrados</p>
							<div class="card shadow-sm">
								<ul class="list-group list-group-flush">
									<li class="list-group-item" v-for="cards">
										<div class="row py-2 align-items-center">
											<div class="col-auto px-4 text-center">
												<div class="text-primary rounded bg-white"><i class="fab fa-cc-mastercard fa-2x rounded px-3 py-2 border border-primary"></i></div>
											</div>
											<div class="col pl-0">
												<p class="m-0 p-0">
													<strong>@{{ card.brand }} com final @{{  }}</strong><br>
													<span class="text-success">Em até 12 parcelas</span>
												</p>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>	
					</div>
					<div class="row pt-4">
						<div class="col">
							<p>Meios disponíveis</p>
							<div class="card shadow-sm">
								<ul class="list-group list-group-flush">
									<a href="{{ route('orders.payment.card') }}" class="list-group-item text-dark">
										<div class="row py-2 align-items-center">
											<div class="col-auto px-4 text-center">
												<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
											</div>
											<div class="col pl-0">
												<p class="m-0 p-0">
													<strong>Cartão de crédito</strong><br>
													<span class="text-success">Em até 12 parcelas</span>
												</p>
											</div>
										</div>
									</a>
									<a href="{{ route('orders.payment.billet') }}" class="list-group-item text-dark">
										<div class="row py-2 align-items-center">
											<div class="col-auto px-4 text-center">
												<div class="text-primary rounded bg-white"><i class="fas fa-barcode fa-2x rounded px-3 py-2 border border-primary"></i></div>
											</div>
											<div class="col pl-0">
												<h5 class="m-0 p-0">Boleto</h5>
											</div>
										</div>
									</a>
								</ul>
							</div>
						</div>	
					</div>
				</div>
			</div>
			
			@include('app.orders._resume')

		</div>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function() {
			
		});
		new Vue({
			el: '#orderPaymentApp',
			data: {
				order: {!! $order->toJson() !!},
				cards: {!! $cards->toJson() !!},
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
