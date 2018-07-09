<div>
	<div id="productEditApp">
		<h2 class="page-title">Produto <small> | Edição de Produto</small></h2>
		<form action="" id="formEditProduct">

			@include('app.products._form')

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button class="btn btn-success float-right" @click.prevent="submitFormEditProduct" title="Salvar">{!! ICONS_OK !!}</i></button>
						<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#productEditApp',
			data: {
				product: {!! $product->toJson() !!},
				categories: []
			},
			created: function(){
				var self = this;
				$.get('{{ route('categories.all') }}', function(data) {
					self.categories = data;
				});
			},
			updated: function(){
				$("select").trigger('change');
			},
			methods:{
				submitFormEditProduct: function (){ 
					var self = this;
					validaForm("#formEditProduct", function(){
						self.product._token = "{{ csrf_token() }}";
						$.put('{{ route('products.update', [$product->id]) }}', self.product, function(data) {
							if(data.error){
								toastr.error('Falha ao atualizar produto!');
							}else{
								toastr.success('Produto atualizado com sucesso');
							}
							parent.jQuery.fancybox.close();
						});
					});
					$("#formEditProduct").submit();
				}
			}
		});
	</script>
</div>