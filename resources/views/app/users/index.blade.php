@extends('layouts.app')

@section('content')
	<div class="page-title">
		<h3>
			Usuários | <small class="text-muted">Listagem de Usuários cadastrados</small>
			<button type="button" class="btn btn-sm btn-primary float-right" title="Novo Usuário" onclick="openFormUser()">{!! ICONS_ADD !!}</button>
		</h3>
	</div>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('app.home') }}">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Usuários</li>
		</ol>
	</nav>
	<div class="row justify-content-center">
		<div class="col">
			<div class="card">
				<div class="card-body">
					<table id="table-users" class="table table-striped table-hover table-pointer">
						<thead>
							<tr>
								<th>Nome</th>
								<th>E-mail</th>
								<th>Nascimento</th>
								<th>Categoria</th>
								<th>Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Nome</th>
								<th>E-mail</th>
								<th>Nascimento</th>
								<th>Categoria</th>
								<th>Status</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#table-users').DataTable({
				ajax: {
					url: "{{ route('app.users.all') }}",
					dataSrc: ""
				},
				columns: [
					{ data: function(data){ return filters.name(data.name); }},
					{ data: function(data){ return filters.default(data.email); }},
					{ data: function(data){ return filters.date(data.birthdate); }},
					{ data: function(data){ return filters.default(data.role); }},
					{ data: function(data){ return filters.yn(data.status); }}
				],
				processing: true,
				order: [],
				fnRowCallback: function( row, data, index, indexFull ) {
					$(row).on('click', function(evt) {
						window.open("{{ route('app.users.show', ['']) }}/"+data.id, evt.ctrlKey ? '_blank' : '_self');
					});
				}
			});
		});

		function openFormUser(){
			$.fancybox.open({
				src: '{{ route('app.users.create') }}',
				type: 'ajax',
				opts: { 
					clickOutside: false,
					clickSlide: false,
					afterClose : function(){
						$('#table-users').DataTable().ajax.reload(null, false); 
					},
				}
			});
		}
	</script>
@endsection
