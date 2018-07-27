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
							<div class="card closed-card">
								<div class="card-header pointer" onclick="toogleHeader(this)">
									<h5 class="card-title py-4 m-0">Modificar envio <span class="float-right"><i class="icon-arrow-up icons"></i></span></h5>
								</div>
								<ul class="list-group list-group-flush">
									<span v-for="method in item.delivery_avaliables" :data-method="method.codigo" :data-item="item.id"
										:class="[method.codigo == item.delivery_form ? 'selected-item' : 'unselected-item']"
										onclick="toogleItem(this)">
										<li class="list-group-item align-items-center" style="cursor:pointer;">
											<div class="row align-items-center p-2">
												<div class="col text-center text-md-left">
													<h5 class="m-0 p-0"><b>@{{ method.codigo | delivery_form }}</b></h5>
													<span class="text-muted" v-if="method.codigo != 0">@{{ method.prazo | deadline }}</span>
													<span class="text-muted" v-else>Entraremos em contato para definir a melhor data</span>
												</div>
												<div class="col-md-3">
													<h5 class="item-price text-center text-md-right m-0 p-0">
														<span v-html="$options.filters.currency_sup(method.valor, true)" v-if="method.valor > 0"></span>
														<span class="free-text"	v-else>Grátis</span>
														<span class="itemIconArrow float-right text-primary pl-3"><i class="icon-arrow-down icons"></i></span>
													</h5>
												</div>
											</div>
										</li>
									</span>
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
								<h4 class="mb-0 py-2">Custo de envio 
									<span class="text-muted pl-3" v-if="order.total_delivery > 0" v-html="$options.filters.currency_sup(order.total_delivery, true)"></span>
									<span class="text-success pl-3" v-else>Grátis</span>
								</h4>
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
		var appDelivery = new Vue({
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
				}
			},
			filters: filters
		});

		function toogleHeader(elHeader){
			$(elHeader).slideToggle('show').next('ul').children('.unselected-item').slideToggle('show', function(){
				$(elHeader).next('ul').children('span.selected-item');
				$(elHeader).find('.item-price').addClass('text-primary');
				$(elHeader).parent().removeClass('opened-card');
				$(elHeader).parent().addClass('closed-card');
			});
			//$(elHeader).slideToggle();
			//$('.tooglable').slideToggle('show');

		}
		function toogleItem(elItem){
			console.log(elItem);
			var elBase = $(elItem).parent().parent();
			var isOpen = elBase.hasClass('opened-card');
			if(!isOpen){
				elBase.find('.unselected-item').slideToggle();
				elBase.find('.card-header').slideToggle();
				$(elBase).removeClass('closed-card').addClass('opened-card');
			}else{
				elBase.find('.selected-item').removeClass('selected-item').addClass('unselected-item');
				$(elItem).removeClass('unselected-item').addClass('selected-item');
				elBase.find('.unselected-item').slideToggle();
				elBase.find('.card-header').slideToggle(function(){
					elBase.removeClass('opened-card').addClass('closed-card');
				});
				let url = "{{ route('orders.item.method.change') }}";
				_axios.put(url, {id: $(elItem).attr('data-item'), codigo: $(elItem).attr('data-method') }).then(function (resp) {
					appDelivery.reloadData();
				}).catch(function(error) {
					toastr.error("Falha ao alterar forma de entrega");
				});
			}
			//$(elHeader).slideToggle();
			//$('.tooglable').slideToggle('show');

		}
	</script>
@endsection
