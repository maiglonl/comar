<div>
	<div id="attributeCreateApp">
		<h2 class="page-title">Atributo <small> | Cadastro Atributo</small></h2>
		<form action="" id="formCreateAttribute">

			@include('app.attributes._form')

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button class="btn btn-success float-right" @click.prevent="submitFormCreateAttribute" title="Salvar">{!! ICONS_OK !!}</i></button>
						<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#attributeCreateApp',
			data: {
				attribute: {
					_token: "{{ csrf_token() }}",
					product_id: '{{ $product_id }}'
				}
			},
			methods:{
				submitFormCreateAttribute: function (){ 
					var self = this;
					validaForm("#formCreateAttribute", function(){
						$.post('{{ route('attributes.store') }}', self.attribute, function(data) {	
							if(data.error){
								toastr.error('Falha ao criar atributo!');
							}else{
								toastr.success('Produto criado com sucesso');
							}
							parent.jQuery.fancybox.close();
						});
					});
					$("#formCreateAttribute").submit();
				}
			}
		});
	</script>
</div>