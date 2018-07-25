@extends('layouts.order')

@section('body_top')
	<div id="orderDeliveryApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-xs-11 col-sm-7">
				<div class="pt-5 pb-3 px-4 mt-3">
					<h4 class="pb-4">Opções de envio para</h4>
					<div class="card border-0 bg-gray-50">
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
								<div class="col-sm-4 col-xs-12 text-sm-right text-xs-center">
									<button class="btn btn-link" @click="changeAdress">Alterar local</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row pt-4" v-for="(item, index) in order.items">
						<div class="col">
							<p>Encomenda @{{ index+1 }}</p>
							<div class="card">
								<div class="card-header">
									<h5 class="card-title p-4 m-0">Modificar envio <span class="float-right"><i class="fas fa-angle-up"></i></span></h5>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item align-items-center hover-item" v-for="method in item.delivery_avaliables" :class="{'selected': method.codigo == item.delivery_form }">
										<div class="row align-items-center" >
											<div class="col">
												@{{ method.codigo | delivery_form }}<br>
												@{{ method.prazo | deadline }}
											</div>
											<div class="col">
												<h5 class="text-right m-0 p-0">
													<span v-html="$options.filters.currency_sup(method.valor, true)" v-if="method.valor > 0"></span>
													<span class="text-success" v-else>Grátis</span>
												</h5>
											</div>
										</div>
									</li>
								</ul>
								<div class="card-body">
									<a href="#" class="card-link">Card link</a>
									<a href="#" class="card-link">Another link</a>
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
