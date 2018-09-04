@extends('layouts.order')

@section('pre-scripts')
	<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
@endsection

@section('body_top')
	<div id="orderCardApp" class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-12 col-sm-8 pb-4">
				<div class="pt-5 pb-3 px-sm-4 mt-3">
					<h4 class="pb-4">Opções de pagamento</h4>
					<div class="card mb-4 border-0 bg-gray-50" v-if="order.card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto text-center">
									<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
								</div>
								<div class="col pl-0">
									<p class="m-0 p-0">
										<strong>Cartão de Crédito @{{ order.card.brand | name }} com final @{{ order.card.number.substr(12,4) }}</strong><br>
										<span class="text-success">Em até 12 parcelas</span>
									</p>
								</div>
								<div class="col-sm-4 col-12 text-sm-right text-center">
									<a href="{{ route('orders.payment') }}" class="btn btn-link">Alterar</a>
								</div>
							</div>
						</div>
					</div>
					<h4 class="pb-2 pt-4">Opções de parcelamento</h4>
					<div class="row pt-2" v-for="(group, index) in order.payment_groups" v-if="group.items.length > 0">
						<div class="col">
							<div class="card closed-card">
								<div class="card-header pointer" onclick="toogleHeader(this)">
									<h5 class="card-title py-4 m-0">Modificar parcelamento <span class="float-right"><i class="icon-arrow-up icons"></i></span></h5>
								</div>
								<ul class="list-group list-group-flush">
									<span v-for="installment in group.installments" 
										:data-quantity="installment.quantity" 
										:data-group="index"
										:class="[installment.quantity == group.selected ? 'selected-item' : 'unselected-item']"
										onclick="toogleItemHandler(this)">
										<li class="list-group-item align-items-center" style="cursor:pointer;">
											<div class="row align-items-center p-2">
												<div class="col text-center text-md-left">
													<h5 class="m-0 p-0">
														<b>@{{ installment.quantity }} x </b>
														<span v-html="$options.filters.currency_sup(installment.installmentAmount, true)"></span>
														<small class="text-success pl-3" v-if="installment.interestFree">sem juros</small>
														<small class="text-muted pl-3" v-else>com juros</small>
													</h5>
												</div>
												<div class="col-md-3 mt-3 mt-sm-0">
													<h5 class="item-price text-center text-md-right m-0 p-0">
														<span v-html="$options.filters.currency_sup(installment.totalAmount, true)"></span>
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
												<span class="text-muted">@{{ item.quantity }} x @{{ item.product.name }}</span><br>
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
								<h4 class="mb-0 py-2">Total 
									<span class="text-muted pl-3" v-html="$options.filters.currency_sup(total, true)"></span>
								</h4>
							</div>
						</div>
					</div>
				</div>
				<div class="px-sm-4 text-right">
					<a href="{{ route('orders.checkout') }}" class="btn btn-primary">Continuar</a>
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
			el: '#orderCardApp',
			data: {
				order: {!! $order->toJson() !!},
				total: 0
			},
			methods: {
				reloadData: function (){
					var self = this;
					$.get('{{ route('orders.find', [$order->id]) }}', function(data) {
						if(data.error){
							toastr.error('Falha ao atualizar o pedido!');
						}else{
							self.order = data;
							self.reloadTotal();
						}
					});
				},
				loadGroups: function(){
					let self = this;
					$.each(self.order.payment_groups, function(index, group) {
						let max = index.split('_')[1];
						if(group.items.length > 0){
							self.setGroupInstallments(max, group.total);
						}
					});
				},
				addItemToGroup: function(item, group){
					let self = this;
					if(self.order.payment_groups.hasOwnProperty(group)){
						self.order.payment_groups[group].items.push(item);
						self.order.payment_groups[group].total += parseFloat(item.total);
					}
				},
				setGroupInstallments: function(interest_free, value){
					let self = this;
					PagSeguroDirectPayment.getInstallments({	
						amount: parseFloat(value),
						brand: self.order.card.brand,
						maxInstallmentNoInterest: parseInt(interest_free),
						success: function(response) {
							if(response.error){
								toastr.error('Falha ao carregar parcelamento!');
							}
							let installments = response.installments[Object.keys(response.installments)[0]];
							let count = self.countInstallmentsFreeInterest(installments);
							self.order.payment_groups['free_'+count].installments = installments;
							self.reloadTotal();
						},
						error: function(response) {
							if(response.error){
								toastr.error('Falha ao carregar parcelamento!');
							}
						}
					});
				},
				countInstallmentsFreeInterest: function(installments){
					let result = 0;
					$.each(installments, function(index, installment){
						result += installment.interestFree ? 1 : 0;
					})
					return result;
				},
				reloadTotal: function(){
					let self = this;
					let result = parseFloat(0);
					$.each(self.order.payment_groups, function(index, group){
						if(group.installments.length > 0){
							result += parseFloat(group.installments[group.selected-1].totalAmount);
						}else{
							result += parseFloat(group.total);
						}
					});
					self.total = result;
				}
			},
			mounted: function(){
				var self = this;
				PagSeguroDirectPayment.setSessionId('{{ $order->session }}');
				self.loadGroups();
				self.reloadTotal();
				$("#formCreateCard").cValidate({
					data: self.card,
					success: function(result){
						$.post('{{ route('orders.payment.select_card') }}', { card_id: result.id }).then(function(){
							location.href = "{{route('orders.checkout')}}";
						});
					}
				});
			},
			filters: filters
		});

		function toogleItemHandler(el){
			toogleItem(el, function(){
				let url = "{{ route('orders.item.installment.change') }}";
				let ids = [];
				$.each(appCard.order.payment_groups[$(el).attr('data-group')].items, function(index, item) {
					ids.push(item.id);
				});
				appCard.order.payment_groups[$(el).attr('data-group')].selected = $(el).attr('data-quantity');
				_axios.put(url, {ids: ids, quantity: $(el).attr('data-quantity') }).then(function (resp) {
					appCard.reloadTotal();
				}).catch(function(error) {
					toastr.error("Falha ao alterar forma de pagamento");
					//location.reload();
				});
					
			});
		}
	</script>
@endsection
