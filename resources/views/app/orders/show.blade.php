@extends('layouts.app')

@section('content')
	<div id="productShowApp">
		<div class="page-title">
			<h3>
				Produtos | <small class="text-muted">Descrição do produto</small>
				<button type="button" class="btn btn-sm btn-primary float-right" title="Editar Produto" @click="openFormEditProduct()">{!! ICONS_EDIT !!}</button>
			</h3>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produtos</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
			</ol>
		</nav>
		<div class="row justify-content-center">
			<div class="col-sm-3">
				<div class="card">
					<div class="card-body">
						<div class="row justify-content-center">
							<div class="col-10">
								<img v-if="product.files.length > 0" :src="product.thumbnails[0]" class="img-fluid img-thumbnail rounded-circle">
								<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid img-thumbnail rounded-circle">
							</div>
						</div>
						<div class="row">
							<div class="col">
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="prod_id">Id:</label>
								<p class="form-control-plaintext" id="prod_id">@{{ product.id | default }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="prod_name">Nome:</label>
								<p class="form-control-plaintext" id="prod_name">@{{ product.name | name }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="prod_categ_name">Categoria:</label>
								<p class="form-control-plaintext" id="prod_categ_name" v-if="product.category">@{{ product.category.name | name }}</p>
								<p class="form-control-plaintext" id="prod_categ_name" v-else>-</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="prod_value">Valor:</label>
								<p class="form-control-plaintext" id="prod_value">
									@{{ product.value_seller | currency(true) }} 
									@if(\App\Helpers\PermHelper::viewValues()) 
										/ @{{ product.value_partner | currency(true) }}
									@endif
								</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="prod_value">Medidas: <small>(P | D | AxLxC)</small></label>
								<p class="form-control-plaintext" id="prod_value">@{{ product.weight }}Kg | @{{ product.diameter }}cm | @{{ product.height }}x@{{ product.width }}x@{{ product.length }}cm</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="prod_description">Descrição:</label>
								<p class="form-control-plaintext" id="prod_description">@{{ product.description | default }}</p>
							</div>
						</div>
						<div class="row att_overlay rounded" v-if="product.attributes" v-for="attribute in product.attributes" @click="openFormEditAttribute(attribute.id)">
							<div class="col">	
								<label class="label-plaintext label-sm">@{{ attribute.name | name }}:</label>
								<p class="form-control-plaintext">@{{ attribute.value | default }}</p>
							</div>
						</div>
						<button type="button" class="btn btn-sm btn-primary float-right" title="Adicionar Atributo" @click="openFormAddAttribute()">{!! ICONS_ADD !!}</button>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="card">
					<div class="card-body">
						<div class="col">
							<div class="row" v-if="product.files.length > 0">
								<div class="col-xs-6 col-md-6 col-lg-4 col-xl-3" style="padding: 10px;" v-for="(file, key) in product.files">
									<div class="bs_hovereffect rounded">
										<img :src="product.thumbnails[key]" class="img-fluid img-thumbnail rounded">
										<div class="overlay">
											<div class="rotate text-center">
												<p class="bs_group1">
													<a href="#" @click.prevent="imagePull(product.id, key)" title="Atualizar Imagem">
														{!! ICONS_ARROW_LEFT !!}
													</a>
													<a href="#" @click.prevent="imagePush(product.id, key)" title="Opções da Imagem">
														{!! ICONS_ARROW_RIGHT !!}
													</a>
												</p>
												<hr><hr>
												<p class="bs_group2">
													<a :href="file" data-fancybox="gallery" title="Visualizar Imagem">
														{!! ICONS_SEARCH !!}
													</a>
													<a href="#" @click.prevent="removeFile(product.id, key)" title="Remover Imagem">
														{!! ICONS_REMOVE !!}
													</a>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row" v-else>
								<div class="col text-center">
									<h5>Nenhuma imagem encontrada!<br><small class="text-muted">Adicione novas imagens para visualizá-las aqui.</small></h5>
								</div>
							</div>
						</div>
						<form method="POST" id="formImageUpload" class="d-none">
							<input type="file" name="fileInput[]" id="fileInput" multiple>
							{{ csrf_field() }}
						</form>
						<button type="button" class="btn btn-primary float-right" title="Adicionar Imagens" @click="openFormImage()">{!! ICONS_UPLOAD !!}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#productShowApp',
			data: {
				product: {!! $product->toJson() !!}
			},
			mounted: function(){
				var self = this;
				validaForm("#formImageUpload", function() {
					var formData = new FormData($('#formImageUpload')[0]);
					$.ajax({
						url: "{{ route('products.image.upload', [$product->id]) }}",
						type: 'POST',
						success: completeHandler = function(data) { 
							self.reloadData();
						},
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						async: true
					}, 'json');
				});
				$('#fileInput').change(function(event) {
					if($('#fileInput').val()){
						$('#formImageUpload').submit();
					}
				});
			},
			methods:{
				reloadData: function (){
					var self = this;
					$.get('{{ route('products.find', [$product->id]) }}', function(data) {
						if(data.error){
							toastr.error('Falha ao atualizar produto!');
						}else{
							self.product = data;
						}
					});
				},
				openFormImage: function(){
					$('#fileInput').click();
				},
				openFormEditProduct: function(){
					var self = this;
					$.fancybox.open({
						src: '{{ route('products.edit', [$product->id]) }}',
						type: 'ajax',
						opts: { 
							clickOutside: false,
							clickSlide: false,
							afterClose : function(){
								self.reloadData(); 
							},
						}
					});
				},
				openFormAddAttribute: function(){
					var self = this;
					$.fancybox.open({
						src: '{{ route('attributes.create', [$product->id]) }}',
						type: 'ajax',
						opts: { 
							clickOutside: false,
							clickSlide: false,
							afterClose : function(){
								self.reloadData(); 
							},
						}
					});
				},
				openFormEditAttribute: function(id){
					var self = this;
					$.fancybox.open({
						src: '{{ route('attributes.edit', ['']) }}/'+id,
						type: 'ajax',
						opts: { 
							clickOutside: false,
							clickSlide: false,
							afterClose : function(){
								self.reloadData(); 
							},
						}
					});
				},
				removeFile: function(id, index){
					var self = this;
					$.delete('{{ route('products.image.delete', ['', '']) }}/'+id+'/'+index, { '_token': "{{ csrf_token() }}" }, function(data) {
						toastr.success('Imagem removida com sucesso!');
						self.reloadData();
					});
				},
				imagePull: function(id, index){
					var self = this;
					$.put('{{ route('products.image.pull', ['', '']) }}/'+id+'/'+index, { '_token': "{{ csrf_token() }}" }, function(data) {
						self.reloadData();
					});
				},
				imagePush: function(id, index){
					var self = this;
					$.put('{{ route('products.image.push', ['', '']) }}/'+id+'/'+index, { '_token': "{{ csrf_token() }}" }, function(data) {
						self.reloadData();
					});
				}
			},
			filters: filters
		});
	</script>
@endsection
