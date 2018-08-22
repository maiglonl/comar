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
					<p>Local de entrega</p>
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
									<a href="{{ route('orders.delivery') }}" class="btn btn-link">Alterar local</a>
								</div>
							</div>
						</div>
					</div>
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
					<p class="mt-4">Forma e dados de pagamento</p>
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
									<a href="{{ route('orders.delivery') }}" class="btn btn-link">Alterar local</a>
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
		PagSeguroDirectPayment.setSessionId('{!! $order->session !!}');
		var appDelivery = new Vue({
			el: '#orderDeliveryApp',
			data: {
				order: {!! $order->toJson() !!},
				user: {!! Auth::user()->toJson() !!},
				senderHash: ""
			},
			computed: {
				payment_installments_groups: function(){
					let self = this;
					let res = {
						sem: {},
						com: {}
					};
					$.each(self.order.items, function(index, item) {
						if(item.payment_installments <= item.product.interest_free){
							let val = res.sem[item.payment_installments+'x'] ? res.sem[item.payment_installments+'x'] : 0;
							res.sem[item.payment_installments+'x'] = val + item.payment_installment;
						}else{
							let val = res.com[item.payment_installments+'x'] ? res.com[item.payment_installments+'x'] : 0;
							res.com[item.payment_installments+'x'] = val + item.payment_installment;
						}
					});
					return res;
				}
			},
			mounted: function(){
				let self = this;
				/*pagSeguro.getSenderHash().then(function(data){
					console.log(data);
					self.senderHash = data.senderHash ? data.senderHash : "";
					console.log(data);
				});*/
			},
			methods:{
				confirmBuy: function (){
					var self = this;
					pagSeguro.getSenderHash().then(function(data){
						self.senderHash = data ? data : "";
						$.post('{{ route('orders.checkout') }}', { senderHash: self.senderHash }, function(data) {
							if(data.error){
								toastr.error('Falha ao realizar operação!');
							}else{
								location.href = '{{ route('orders.checkout.success')}}';
							}
						});
					});
				},
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

		function toogleItemHandler(el){
			toogleItem(el, function(){
				let url = "{{ route('orders.item.method.change') }}";
				_axios.put(url, {id: $(el).attr('data-item'), codigo: $(el).attr('data-method') }).then(function (resp) {
					appDelivery.reloadData();
				}).catch(function(error) {
					toastr.error("Falha ao alterar forma de entrega");
					location.reload();
				});
			});
		}
	</script>
@endsection
