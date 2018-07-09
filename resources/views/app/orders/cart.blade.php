@extends('layouts.app')

@section('content')
	<div id="orderCartApp">
		<div class="page-title">
			<h3>
				Pedidos | <small class="text-muted">Pedido em andamento</small>
			</h3>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('products.index') }}">Pedidos</a></li>
				<li class="breadcrumb-item active" aria-current="page">Novo pedido</li>
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
										<label class="label-plaintext label-sm" for="prod_value">Valor:</label>
										<p class="form-control-plaintext" id="prod_value">
											@{{ product.value_partner | currency(true) }} 
											@if(\App\Helpers\PermHelper::viewValues()) 
												/ @{{ product.value_seller | currency(true) }}
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
								<div class="row" v-if="product.attributes" v-for="attribute in product.attributes">
									<div class="col">
										<label class="label-plaintext label-sm" for="prod_value">@{{ attribute.name }}:</label>
										<p class="form-control-plaintext" id="prod_value">@{{ attribute.value }}</p>
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
			el: '#orderCartApp',
			data: {
				order: {!! $order->toJson() !!}
			},
			mounted: function(){
			},
			methods:{
			},
			filters: filters
		});
	</script>
@endsection
