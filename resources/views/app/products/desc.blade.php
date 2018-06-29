@extends('layouts.app')

@section('content')
	<div id="productDescApp">
		<div class="page-title">
			<h3>
				Produtos | <small class="text-muted">Descrição do produto</small>
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
			<div class="col">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<img v-if="product.files.length > 0" :src="product.files[0]" class="img-fluid img-thumbnail rounded">
								<img v-else src="{{ DEFAULT_IMAGE_PRODUCTS }}" class="img-fluid img-thumbnail rounded">
								
								<div class="row p-0 m-0 mt-2">
									<div class="col-sm-3 p-2" v-for="(file, index) in product.files" v-if="index < 4">
										<a :href="file" :data-fancybox="'gallery_'+product.id">
											<img v-if="product.files.length > 0" :src="product.thumbnails[index]" class="img-fluid img-thumbnail rounded">
										</a>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="row">
									<div class="col">
										<h3 class="section-title" id="prod_name">@{{ product.name | name }} <small v-if="product.category" class="text-muted">| @{{ product.category.name | name }}</small></h3>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<label class="label-plaintext label-sm" for="prod_value">Valores:</label>
										<p class="form-control-plaintext" id="prod_value">@{{ product.value_seller | currency(true) }} / @{{ product.value_partner | currency(true) }}</p>
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
							</div>
						</div>
						<div class="row">
							<div class="col">
								<hr>
							</div>
						</div>
						<div class="row" v-if="product.attributes" v-for="attribute in product.attributes">
							<div class="col">	
								<label class="label-plaintext label-sm">@{{ attribute.name | name }}:</label>
								<p class="form-control-plaintext">@{{ attribute.value | default }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#productDescApp',
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
