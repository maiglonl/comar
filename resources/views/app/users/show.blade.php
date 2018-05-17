@extends('layouts.app')

@section('content')
	
	<div class="page-title">
		<h3>
			Usuários | <small class="text-muted">Descrição do produto</small>
			<button type="button" class="btn btn-sm btn-primary float-right" title="Editar Usuário" onclick="openFormEditProduct()">{!! ICONS_EDIT !!}</button>
		</h3>
	</div>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('app.home') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('app.users.index') }}">Usuários</a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
		</ol>
	</nav>
	<div class="row justify-content-center">
		<div class="col-sm-4">
			<div class="card">
				<div class="card-body">
					<div class="form-group row">
						<label for="id" class="col-sm-4 col-form-label">Id:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="id" value="{{ $user->id }}">
						</div>
					</div>
					<div class="form-group row">
						<label for="name" class="col-sm-4 col-form-label">Nome:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="name" value="{{ $user->name }}">
						</div>
					</div>
					<div class="form-group row">
						<label for="email" class="col-sm-4 col-form-label">E-mail:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="email" value="{{ $user->email }}">
						</div>
					</div>
					<div class="form-group row">
						<label for="city" class="col-sm-4 col-form-label">Cidade:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="city" value="{{ $user->city }}">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card">
				<div class="card-body">
					<div class="form-controler">
						<label>Imagens:</label>
						<div class="files-input">
							<input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
		});

		function openFormEditProduct(){
			$.fancybox.open({
				src: '{{ route('app.users.edit', [$user->id]) }}',
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
	</script>
@endsection
