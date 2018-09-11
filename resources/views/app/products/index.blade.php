@extends('layouts.admin')

@section('content')
	<div class="page-title pb-3">
		<h3>
			Produtos | <small class="text-muted">Listagem de Produtos cadastrados</small>
			<button type="button" class="btn btn-sm btn-primary float-right" title="Novo Produto" onclick="openFormProduct()">{!! ICONS_ADD !!}</button>
		</h3>
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
	<script type="text/javascript">
		$(document).ready(function() {
			$('#table-products').DataTable({
				ajax: {
					url: "{{ route('products.all') }}",
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
						window.open("{{ route('products.show', ['']) }}/"+data.id, evt.ctrlKey ? '_blank' : '_self');
					});
				}
			});
		});

		function openFormProduct(){
			$.fancybox.open({
				src: '{{ route('products.create') }}',
				type: 'ajax',
				opts: { 
					clickOutside: false,
					clickSlide: false,
					afterClose : function(){
						$('#table-products').DataTable().ajax.reload(null, false); 
					},
				}
			});
		}
	</script>
@endsection
