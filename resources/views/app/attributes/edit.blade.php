<div>
	<div id="attributeEditApp">
		<h2 class="page-title">Atributo <small> | Edição de Atributo</small></h2>
		<form action="" id="formEditAttribute">

			@include('app.attributes._form')

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group text-center">
						<button class="btn btn-success float-right" @click.prevent="submitFormEditAttribute" title="Salvar">{!! ICONS_OK !!}</i></button>
						<button type="button" class="btn btn-outline-danger center" @click.prevent="submitFormDeleteAttribute"  title="Excluir">{!! ICONS_REMOVE !!}</i></button>
						<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#attributeEditApp',
			data: {
				attribute: {!! $attribute->toJson() !!}
			},
			methods:{
				submitFormEditAttribute: function (){ 
					var self = this;
					validaForm("#formEditAttribute", function(){
						self.attribute._token = "{{ csrf_token() }}";
						$.put('{{ route('attributes.update', [$attribute->id]) }}', self.attribute, function(data) {
							if(data.error){
								toastr.error('Falha ao atualizar atributo!');
							}else{
								toastr.success('Atributo atualizado com sucesso');
							}
							parent.jQuery.fancybox.close();
						});
					});
					$("#formEditAttribute").submit();
				},
				submitFormDeleteAttribute: function (){ 
					var token = {'_token': "{{ csrf_token() }}"};
					$.delete('{{ route('attributes.destroy', [$attribute->id]) }}', token, function(data) {
						toastr.success('Atributo removido com sucesso');
						parent.jQuery.fancybox.close();
					});
				}
			}
		});
	</script>
</div>