@extends('layouts.app')

@section('content')
	<div class="container-fluid p-0 bg-white" id="appOrdersList">
		<div class="page-title">
			<h3>Compras | <small>Resumo do pedido # @{{order.id}}</small></h3>
		</div>
		<div class="row">
			<div class="col-sm-7">
				<div class="card p-2 mt-4 mb-4 ml-4 bg-gray-50">
					<div class="card-body">
						<p class="mb-2"><b>Resumo da Compra</b></p>
						<hr class="mt-0 mb-4">
						<p class="mb-2">Produtos(@{{ order.items.length }}) <span class="float-right" v-html="$options.filters.currency_sup(order.total_items)"></span></p>
						<p v-if="order.total_delivery > 0">Envio <span class="float-right" v-html="$options.filters.currency_sup(order.total_delivery, true)"></span></p>
						<p v-else>Envio <span class="float-right text-success">Grátis</span></p>
						{{-- <p>Desconto <span class="float-right"></span></p> --}}
						<hr class="my-4">
						<p class="mb-2"><b>Você pagou</b></p>
						<span v-if="order.payment_method == '{{ PAYMENT_METHOD_CREDIT_CARD }}'">
							<p class="h5 mb-2" v-for="group in order.payment_groups" v-if="group.installments.length >= group.selected -1">
								@{{ group.selected }}x <small class="text-success" v-if="group.installments[group.selected-1].interestFree">sem juros</small>
								<span class="float-right" v-html="$options.filters.currency_sup(group.installments[group.selected-1].installmentAmount)"></span>
							</p>
						</span>
						<span v-else>
							<p class="h5 mb-2">1x <span class="float-right" v-html="$options.filters.currency_sup(order.total)"></span></p>
						</span>
						</p>
						<hr class="my-4">
						<p>Total <span class="float-right" v-html="$options.filters.currency_sup(total)"></span></p>
					</div>
				</div>
				<div class="card p-2 mt-4 mb-4 ml-4 bg-gray-50">
					<div class="card-body">
						<div class="row align-items-center" v-if="order.payment_method == '{{ PAYMENT_METHOD_CREDIT_CARD }}'">
							<div class="col-auto text-center">
								<div class="text-primary rounded bg-white"><i class="far fa-credit-card fa-2x rounded px-3 py-2 border border-primary"></i></div>
							</div>
							<div class="col pl-0">
								<p class="m-0 p-0">
									<strong>Cartão de Crédito @{{ order.card.brand | name }} com final @{{ order.card.number.substr(12,4) }}</strong>
								</p>
								<p class="m-0 p-0">Confirmado em 12/12/2012</p>
								<p class="m-0 p-0">Aguardando confirmação de pagamento</p>
							</div>
						</div>
						<div class="row align-items-center" v-else>
							<div class="col-auto text-center">
								<div class="text-primary rounded bg-white"><i class="fas fa-barcode fa-2x rounded px-3 py-2 border border-primary"></i></div>
							</div>
							<div class="col pl-0">
								<p class="m-0 p-0"><strong> Pagamento à vista no Boleto</strong></p>
								<p class="m-0 p-0" >Confirmado em 12/12/2012</p>
								<p class="m-0 p-0" >Aguardando confirmação de pagamento</p>
							</div>
						</div>
					</div>
				</div>
				<div class="card p-2 mt-4 mb-4 ml-4 bg-gray-50">
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
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="card p-4 mt-4 mr-4 mb-4">
					<div class="card-body">
						<div class="row" v-for="(item, index) in order.items">
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
										<span class="text-muted">Valor: 
											<span class="text-muted" v-html="$options.filters.currency_sup(item.value, true)"></span>
										</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card p-4 mt-4 mr-4 mb-4">
					<div class="card-body">
						<p class="mb-2" v-for="(task, index) in order.tasks">
							@{{ task.stage.description }}
							<span class="float-right text-success" v-html="$options.filters.datetime(task.date_conclusion).split(' ')[0]" v-if="task.date_conclusion"></span>
							<span class="float-right text-info" v-else>pendente</span>
						</p>
					</div>
				</div>
				<div class="card p-4 mt-4 mr-4 mb-4">
					<div class="card-body">
						<p class="mb-2" v-for="bill in order.bills">
							<span v-if="bill.type == 'debit'">
								<span class="h5" v-html="$options.filters.currency_sup(bill.value, true)"></span><span class="text-muted h5"> - @{{ bill.user.name }}</span>
								<span class="float-right text-success" v-html="$options.filters.datetime(bill.updated_at).split(' ')[0]" v-if="bill.done"></span>
								<span class="float-right text-info" v-else>pendente</span>
							</span>
						</p>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#appOrdersList',
			data: {
				order: {!! $order->toJson() !!},
				total: 0
			},
			mounted: function(){
				let self = this;
				let result = parseFloat(0);
				$.each(self.order.payment_groups, function(index, group){
					if(group.installments.length >= group.selected-1){
						result += parseFloat(group.installments[group.selected-1].totalAmount);
					}else{
						result += parseFloat(group.total);
					}
				});
				self.total = result;
			},
			methods:{
				getStatusClass: function(order){
					switch(parseInt(order.status.id)){
						case {{ STATUS_ORDER_CANCELADO }}: return "text-danger"; break;
						case {{ STATUS_ORDER_ETREGUE }}: return "text-success"; break;
						default: return "text-primary"; break;
					}
				}
			},
			filters: filters
		});
	</script>
@endsection
