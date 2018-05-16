<div>
	<div id="userEditApp">
		<h2 class="page-title">Usuário <small> | Edição de Usuário</small></h2>
		<form action="" id="formEditUser">

			@include('app.users._form')

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button class="btn btn-success float-right" @click.prevent="submitFormEditUser" title="Salvar">{!! ICONS_OK !!}</i></button>
						<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#userEditApp',
			data: {
				user: {!! $user->toJson() !!}
			},
			methods:{
				submitFormEditUser: function (){ 
					var self = this;
					validaForm("#formEditUser", function(){
						self.user._token = "{{ csrf_token() }}";
						$.put('{{ route('app.users.update', [$user->id]) }}', self.user, function(data) {
							if(data.error){
								toastr.danger('Falha ao atualizar usuário!');
							}else{
								toastr.success('Usuário atualizado com sucesso');
							}
							parent.jQuery.fancybox.close();
						});
					});
					$("#formEditUser").submit();
				}
			}
		});
	</script>
</div>