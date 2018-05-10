<div>
	<div id="productCreateApp">
		<h2 class="page-title">Produto <small> | Cadastro de novo Produto</small></h2>
		<form action="" id="formProduct">

			@include('app.products._form')

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button class="btn btn-success pull-right" @click.prevent="submitFormProduct" title="Salvar"><i class="fa fa-check"></i></button>
						<button type="button" class="btn btn-danger pull-left closeFancybox" title="Cancelar"><i class="fa fa-times"></i></button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#productCreateApp',
			data: {
				product: {
					_token: "{{ csrf_token() }}"
				}
			},
			methods:{
				submitFormProduct: function (){ 
					var self = this;
					validaForm("#formProduct", function(){
						$.post('{{ route('app.products.store') }}', self.product, function(data) {	
							if(data.error){
								toastr.danger('Falha ao criar produto!');
							}else{
								toastr.success('Produto criado com sucesso');
							}
							parent.jQuery.fancybox.close();
						});
					});
					$("#formProduct").submit();
				}
			}
		});
	</script>
</div>