<div>
	<div id="productCreateApp">
		<h2 class="page-title">Produto <small> | Cadastro de novo Produto</small></h2>
		<form action="" id="formCreateProduct">

			@include('app.products._form')

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button class="btn btn-success float-right" @click.prevent="submitFormCreateProduct" title="Salvar">{!! ICONS_OK !!}</i></button>
						<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
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
				submitFormCreateProduct: function (){ 
					var self = this;
					validaForm("#formCreateProduct", function(){
						$.post('{{ route('app.products.store') }}', self.product, function(data) {	
							if(data.error){
								toastr.danger('Falha ao criar produto!');
							}else{
								toastr.success('Produto criado com sucesso');
							}
							parent.jQuery.fancybox.close();
						});
					});
					$("#formCreateProduct").submit();
				}
			}
		});
	</script>
</div>