@extends('layouts.order')

@section('body_top')
	<div id="orderDeliveryApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-12 col-sm-8 pb-4">
				<div class="pt-5 pb-3 px-sm-4 mt-3">
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
								<div class="col-sm-4 col-12 text-sm-right text-center">
									<button class="btn btn-link" @click="changeAdress">Alterar local</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row pt-4" v-for="(group, index) in itemGroups">
						<div class="col">
							<div class="card closed-card">
								<div class="card-header pointer" onclick="toogleHeader(this)">
									<h5 class="card-title py-4 m-0">Modificar envio <span class="float-right"><i class="icon-arrow-up icons"></i></span></h5>
								</div>
								<ul class="list-group list-group-flush">
									<span v-for="method in group.delivery_availables" 
										:data-method="method.codigo" 
										:data-group="index"
										:class="[method.codigo == group.delivery_form ? 'selected-item' : 'unselected-item']"
										onclick="toogleItemHandler(this)">
										<li class="list-group-item align-items-center" style="cursor:pointer;">
											<div class="row align-items-center p-2">
												<div class="col text-center text-md-left">
													<h5 class="m-0 p-0"><b>@{{ method.codigo | delivery_form }}</b></h5>
													<span class="text-muted" v-if="method.codigo != 0">@{{ method.prazo | deadline }}</span>
													<span class="text-muted" v-else>Entraremos em contato para definir a melhor data</span>
												</div>
												<div class="col-md-3 mt-3 mt-sm-0">
													<h5 class="item-price text-center text-md-right m-0 p-0">
														<span v-html="$options.filters.currency_sup(method.valor, true)" v-if="method.valor > 0"></span>
														<span class="free-text"	v-else>Grátis</span>
														<span class="item-icon-arrow float-right text-primary pl-3"><i class="icon-arrow-down icons"></i></span>
													</h5>
												</div>
											</div>
										</li>
									</span>
								</ul>
								<div class="card-body">
									<div class="row align-items-center" v-for="item in group.items">
										<div class="col-auto text-center" style="width: 70px">
											<img v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
											<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
										</div>
										<div class="col pl-0">
											<p class="m-0 p-0">
												<span class="text-muted">@{{ item.product.name }}</span><br>
												<span class="text-muted">Quantidade: @{{ item.amount }}</span>
											</p>
										</div>
									</div>
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
				<div class="px-sm-4 text-right">
					<a href="{{ route('orders.payment') }}" class="btn btn-primary">Continuar</a>
				</div>
			</div>
			@include('app.orders._resume')
		</div>
	</div>
	
	<script type="text/javascript">
		if(performance.navigation.type == 2){
		   location.reload(true);
		}
		var defaultGroupItem = {delivery_availables: [], delivery_cost: 0, delivery_form: 0, delivery_time: 0, items: [] };
		var appDelivery = new Vue({
			el: '#orderDeliveryApp',
			data: {
				order: {!! $order->toJson() !!},
				itemGroups: {}
			},
			created: function(){
				this.setupItemGroups();
			},
			methods:{
				reloadData: function (){
					var self = this;
					$.get('{{ route('orders.find', [$order->id]) }}', function(data) {
						if(data.error){
							toastr.error('Falha ao atualizar o pedido!');
						}else{
							self.order = data;
							self.setupItemGroups();
						}
					});
				},
				setupItemGroups: function(){
					var self = this;
					self.itemGroups = {};
					$.each(self.order.items, function(index, item) {
						console.log(item);
						switch(item['delivery_availables'].length){
							case 1:
								self.addItemToGroup(item, 'str');
								break;
							case 2:
								self.addItemToGroup(item, 'nor');
								break;
							case 3:
								self.addItemToGroup(item, 'all');
								break;
							default:
								self.addItemToGroup(item, 'nor');
								break;
						}
					});
				},
				addItemToGroup: function(item, group){
					let self = this;
					let pref = item.free_shipping == 1 ? 'free_' : 'payed_';
					if(!self.itemGroups.hasOwnProperty(pref+group)){
						self.itemGroups[pref+group] = {delivery_availables: [], delivery_cost: 0, delivery_form: 0, delivery_time: 0, items: [] };
					}
					self.itemGroups[pref+group]['delivery_cost'] += parseFloat(item.delivery_cost);
					self.itemGroups[pref+group]['delivery_form'] = parseFloat(item.delivery_form);
					self.itemGroups[pref+group]['delivery_time'] = Math.max(parseFloat(self.itemGroups[pref+group]['delivery_time']), parseFloat(item.delivery_time));
					self.itemGroups[pref+group]['items'].push(item);
					$.each(item.delivery_availables, function(index, item_del) {
						let key = null;
						$.each(self.itemGroups[pref+group].delivery_availables, function(index, group_del) {
							if(group_del.codigo == item_del.codigo){
								key = index;
								return;
							}
						});
						if(key == null){
							self.itemGroups[pref+group].delivery_availables.push({
								'codigo': parseFloat(item_del.codigo),
								'prazo': parseFloat(item_del.prazo),
								'valor': parseFloat(item_del.valor),
							});
						}else{
							self.itemGroups[pref+group].delivery_availables[key]['valor'] += parseFloat(item_del.valor);
							self.itemGroups[pref+group].delivery_availables[key]['prazo'] = Math.max(parseFloat(self.itemGroups[pref+group].delivery_availables[key]['prazo']), parseFloat(item_del.prazo));
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
								location.reload(); 
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
				let ids = [];
				$.each(appDelivery.itemGroups[$(el).attr('data-group')].items, function(index, item) {
					ids.push(item.id);
				});
				console.log(ids);
				_axios.put(url, {ids: ids, codigo: $(el).attr('data-method') }).then(function (resp) {
					appDelivery.reloadData();
				}).catch(function(error) {
					toastr.error("Falha ao alterar forma de entrega");
					location.reload();
				});
					
			});
		}
	</script>
@endsection
