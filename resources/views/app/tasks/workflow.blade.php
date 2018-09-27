@extends('layouts.admin')

@section('content')
	<div id="workflowApp">
		<div class="page-title pb-3">
			<h3>
				Tarefas | <small class="text-muted">Listagem de Tarefas pendentes</small>
			</h3>
		</div>
		<nav class="nav nav-tabs nav-fill nav-inside-tabs">
			<a class="nav-item nav-link py-3" data-toggle="tab" v-for="stage in stages" :href="'#stageTabContent_'+stage.id" :id="'stageTab_'+stage.id" :title="stage.description">@{{ stage.name }} (@{{ stage.open_tasks.length }})</a>
		</nav>
		<div class="tab-content">
			<div id="home" class="tab-pane fade" v-for="stage in stages" :id="'stageTabContent_'+stage.id">
				<div :class="{'card' : stage.open_tasks.length > 0 }">
					<div class="list-group list-group-flush">
						<div class="list-group-item" :id="'taskItem_'+task.id" v-for="task in stage.open_tasks">
							<div class="row">
								<div class="col-11">
									<h5 class="w-100">
										<span class="float-right">@{{ task.order.client.name | name }}</span>
										<span v-for="(payment, id, key) in task.order.payment">
											<span v-if="key > 0"> + </span>
											<span class="h4" v-html="$options.filters.currency_sup(payment.total)"></span>
										</span>
										<span class="text-primary strong"> - @{{ task.order.payment_method | payment_name }}</span><br><small>@{{ task.order.created_at | datetime }}</small>
									</h5>
									<!--
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
								-->
								</div>
								<div class="col">
									<button type="button" class="btn btn-outline-primary h-100 w-100" @click="finishTask(task.id)"><i class="fas fa-check"></i></button>
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
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#workflowApp',
			data: {
				stages: {!! json_encode($stages) !!}
			},
			mounted: function(){
				$('#stageTab_1').click();
			},
			methods:{
				finishTask: function (id){ 
					var self = this;
					$.post('{{route('tasks.finish', [''])}}/'+id, null, function(data) {
						// $('#taskItem_'+id).hide('400');
						self.reloadData();
					});
				},
				reloadData: function(){
					var self = this;
					$.get('{{route('stages.all_with_tasks', [''])}}', null, function(data) {
						self.stages = data;
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
