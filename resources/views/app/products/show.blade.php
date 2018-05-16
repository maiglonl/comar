@extends('layouts.app')

@section('content')
	<script type="text/javascript" src="{{ mix('js/fileinput.js') }}"></script>
	
	<div class="page-title">
		<h3>
			Produtos | <small class="text-muted">Descrição do produto</small>
			<button type="button" class="btn btn-sm btn-primary float-right" title="Editar Produto" onclick="openFormEditProduct()">{!! ICONS_EDIT !!}</button>
		</h3>
	</div>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('app.home') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('app.products.index') }}">Produtos</a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
		</ol>
	</nav>
	<div class="row justify-content-center">
		<div class="col-sm-4">
			<div class="card">
				<div class="card-body">
					<div class="form-group row">
						<label for="id" class="col-sm-4 col-form-label">Id:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="id" value="{{ $product->id }}">
						</div>
					</div>
					<div class="form-group row">
						<label for="name" class="col-sm-4 col-form-label">Name:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="name" value="{{ $product->name }}">
						</div>
					</div>
					<div class="form-group row">
						<label for="value" class="col-sm-4 col-form-label">Valor:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="value" value="{{ $product->value }}">
						</div>
					</div>
					<div class="form-group row">
						<label for="description" class="col-sm-4 col-form-label">Descrição:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control-plaintext" id="description" value="{{ $product->description }}">
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
		$(document).ready(function() {$('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
		});

		function openFormEditProduct(){
			$.fancybox.open({
				src: '{{ route('app.products.edit', [$product->id]) }}',
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
