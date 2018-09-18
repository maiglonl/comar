@extends('layouts.admin')

@section('content')
	<div id="workflowApp">
		<div class="page-title pb-3">
			<h3>
				Tarefas | <small class="text-muted">Listagem de Tarefas pendentes</small>
			</h3>
		</div>
		<div class="row">
			<div class="col" v-for="stage in stages">
				@{{ stage.name }} (@{{ stage.open_tasks.length }})
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col">
				<div class="card">
					<div class="card-body">
						<table id="table-products" class="table table-striped table-hover table-pointer">
							<thead>
								<tr>
									<th>Nome</th>
									<th>Valores</th>
									<th>Status</th>
									<th>Imagens</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Nome</th>
									<th>Valores</th>
									<th>Status</th>
									<th>Imagens</th>
								</tr>
							</tfoot>
						</table>
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
			methods:{
				finishTask: function (id){ 
					var self = this;
				}
			}
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
