@extends('layouts.order')

@section('body_top')
	<div id="orderDeliveryApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-xs-11 col-sm-7">
				<div class="pt-5 pb-3 px-4 mt-3">
					<h4 class="pb-4">Opções de envio para</h4>
					<div class="card border-0">
						<ul class="list-group list-group-flush">
							<li class="list-group-item bg-gray-50">
								<div class="row py-2 align-items-center">
									<div class="col-auto px-4 text-center">
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
									<div class="col-sm-4 col-xs-12 text-sm-right text-xs-center">
										<button class="btn btn-link" @click="changeAdress">Alterar local</button>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="row pt-4" v-for="(item, index) in order.items">
						<div class="col">
							<p>Encomenda @{{ index+1 }}</p>
							<div class="card">
								<div class="card-body">
									DADOS DO FRETE
								</div>
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
					<a href="{{ route('orders.payment') }}" class="btn btn-primary">Continuar</a>
				</div>
			</div>

			@include('app.orders._resume')

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
				console.log(this.order);
			},
			methods:{
				reloadData: function (){
					var self = this;
					$.get('{{ route('orders.find', [$order->id]) }}', function(data) {
						if(data.error){
							toastr.error('Falha ao atualizar o pedido!');
						}else{
							self.order = data;
						}
					});
				},
				changeAdress: function(){
					var self = this;
					$.fancybox.open({
						src: '{{ route('orders.form.address') }}',
						type: 'ajax',
						opts: { 
							clickOutside: false,
							clickSlide: false,
							afterClose : function(){
								self.reloadData(); 
							},
						}
					});
				},
			},
			filters: filters
		});
	</script>
@endsection
