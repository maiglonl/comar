@extends('layouts.admin')

@section('content')
	<div id="workflowApp">
		<div class="page-title pb-3">
			<h3>
				Faturas | <small class="text-muted">Listagem de Contas</small>
			</h3>
		</div>
		<nav class="nav nav-tabs nav-fill nav-inside-tabs">
			<a class="nav-item nav-link py-3" data-toggle="tab" href="#debitTabContent" id="debitTab" title="Contas à pagar">À pagar (@{{ toPay.length }})</a>
			<a class="nav-item nav-link py-3" data-toggle="tab" href="#creditTabContent" id="creditTab" title="Contas à receber">À receber (@{{ toReceive.length }})</a>
			<a class="nav-item nav-link py-3" data-toggle="tab" href="#debitTabContent" id="debitTab" title="Contas Recebidas">Recebido (@{{ received.length }})</a>
		</nav>
		<div class="tab-content">
			<div class="tab-pane fade" id="toPayTabContent">
				<div :class="{'card' : stage.open_tasks.length > 0 }">
					<div class="list-group list-group-flush">
						<div class="list-group-item list-item-lb list-item-bg pointer" v-for="task in stage.open_tasks" :id="'taskItem_'+task.id" data-toggle="collapse" :data-target="'#taskItemDesc_'+task.id">
							<div class="row">
								<div class="col">
									<h5 class="m-0 w-100">
										<button type="button" class="btn btn-outline-primary float-right ml-4" style="height: 50px; width: 50px;" @click="finishTask(task.id, 'task')"><i class="fas fa-check"></i></button>
										<span class="float-right">@{{ task.order.client.name | name }}</span>
										<span v-for="(payment, id, key) in task.order.payment">
											<span v-if="key > 0"> + </span>
											<span class="h4" v-html="$options.filters.currency_sup(payment.total)"></span>
										</span>
										<span class="text-primary strong"> - @{{ task.order.payment_method | payment_name }}</span><br><small>@{{ task.order.created_at | datetime }}</small>
									</h5>
								</div>
							</div>
							<div class="collapse" :id="'taskItemDesc_'+task.id">
								<label class="small">Produtos:</label>
								<div class="row align-items-center" v-for="item in task.order.items">
									<div class="col-auto text-center" style="width: 60px">
										<img v-if="item.product.thumbnails.length > 0" :src="item.product.thumbnails[0]" class="img-fluid">
										<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid">
									</div>
									<div class="col pl-0">
										<p class="m-0 p-0">
											<span class="text-muted">@{{ item.quantity }} x @{{ item.product.name }}</span><br>
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col text-center">
										<a :href="'{{ route('orders.home', ['']) }}/'+task.order.id">Acessar detalhes do pedido</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" v-if="stage.open_tasks.length == 0">
					<div class="col text-center p-4">
						<h4>Nenhuma tarefa pendente nesta etapa!</h4>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="toReceiveTabContent">
				<div :class="{'card' : credit_bills.length > 0 }">
					<div class="list-group list-group-flush">
						<div class="list-group-item list-item-lb list-item-bg pointer" :id="'credit_billItem_'+credit_bill.id" v-for="credit_bill in credit_bills" data-toggle="collapse" :data-target="'#creditBillItemDesc_'+credit_bill.id">
							<div class="row">
								<div class="col">
									<h5 class="m-0 w-100">
										<button type="button" class="btn btn-outline-primary float-right ml-4" style="height: 50px; width: 50px;" @click="finishTask(credit_bill.id, 'credit')"><i class="fas fa-check"></i></button>
										<span class="float-right text-right">@{{ credit_bill.order.client.name | name }}<br><small>@{{ credit_bill.name | name }}</small></span>
										<span class="h4" v-html="$options.filters.currency_sup(credit_bill.value)"></span>
									</h5>
								</div>
							</div>
							<div class="collapse" :id="'creditBillItemDesc_'+credit_bill.id">
								<label class="small">Dados do pedido:</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row" v-if="credit_bills.length == 0">
					<div class="col text-center p-4">
						<h4>Nenhuma tarefa pendente nesta etapa!</h4>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="receivedTabContent">
				<div :class="{'card' : debit_bills.length > 0 }">
					<div class="list-group list-group-flush">
						<div class="list-group-item list-item-lb list-item-bg pointer" :id="'debit_billItem_'+debit_bill.id" v-for="debit_bill in debit_bills" data-toggle="collapse" :data-target="'#debitBillItemDesc_'+debit_bill.id">
							<div class="row">
								<div class="col">
									<h5 class="m-0 w-100">
										<button type="button" class="btn btn-outline-primary float-right ml-4" style="height: 50px; width: 50px;" @click="finishTask(credit_bill.id, 'debit')"><i class="fas fa-check"></i></button>
										<span class="float-right text-right">@{{ debit_bill.user.name | name }}<br><small>@{{ debit_bill.name | name }}</small></span>
										<span class="h4" v-html="$options.filters.currency_sup(debit_bill.value)"></span>
									</h5>
								</div>
							</div>
							<div class="collapse" :id="'debitBillItemDesc_'+debit_bill.id">
								<label class="small">Dados do pedido:</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row" v-if="debit_bills.length == 0">
					<div class="col text-center p-4">
						<h4>Nenhuma tarefa pendente nesta etapa!</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#workflowApp',
			data: {
				toPay: {!! json_encode($toPay) !!},
				toReceive: {!! json_encode($toReceive) !!},
				received: {!! json_encode($received) !!}
			},
			mounted: function(){
				$('#stageTab_1').click();
			},
			methods:{
				finishTask: function (id, type){ 
					var self = this;
					switch(type){
						case 'task': 
							$.post('{{route('tasks.finish', [''])}}/'+id, null, function(data) {
								self.reloadData();
							});
							break;
						case 'credit': console.log(123); break;
						case 'debit': console.log(123); break;
					}
				},
				reloadData: function(){
					var self = this;
					$.get('{{route('stages.all_with_tasks', [''])}}', null, function(data) {
						self.stages = data;
					});	
					$.get('{{route('bills.all_open_credit')}}', null, function(data) {
						self.credit_bills = data;
					});	
					$.get('{{route('bills.all_open_debit')}}', null, function(data) {
						self.debit_bills = data;
					});	
				}
			},
			filters: filters
		});
		/*$(document).ready(function() {
			$('#table-products').DataTable({
				ajax: {
					url: "{ { route('tasks.all') }}",
					dataSrc: ""
				},
				columns: [
					{ data: function(data){ return filters.name(data.name); }},
					{ data: function(data){ return filters.currency(data.value_seller, true)+" / "+filters.currency(data.value_partner, true); }},
					{ data: function(data){ return filters.yn(data.status); }},
					{ data: function(data){ return data.files.length; }}
				],
				processing: true,
				order: [],
				fnRowCallback: function( row, data, index, indexFull ) {
					$(row).on('click', function(evt) {
						window.open("{ { route('products.show', ['']) }}/"+data.id, evt.ctrlKey ? '_blank' : '_self');
					});
				}
			});
		});

		function openFormProduct(){
			$.fancybox.open({
				src: '{ { route('products.create') }}',
				type: 'ajax',
				opts: { 
					clickOutside: false,
					clickSlide: false,
					afterClose : function(){
						$('#table-products').DataTable().ajax.reload(null, false); 
					},
				}
			});
		}*/
	</script>
@endsection
