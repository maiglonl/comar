<div>
	<div id="userCreateApp">
		<h2 class="page-title">Usuário <small> | Cadastro de novo Usuário</small></h2>
		<form action="" id="formCreateUser">

			@include('app.users._form')

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button class="btn btn-success float-right" @click.prevent="submitFormCreateUser" title="Salvar">{!! ICONS_OK !!}</i></button>
						<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#userCreateApp',
			data: {
				user: {
					_token: "{{ csrf_token() }}"
				}
			},
			methods:{
				submitFormCreateUser: function (){ 
					var self = this;
					validaForm("#formCreateUser", function(){
						$.post('{{ route('app.users.store') }}', self.user, function(data) {	
							if(data.error){
								toastr.danger('Falha ao criar usuário!');
							}else{
								toastr.success('Usuário criado com sucesso');
							}
							parent.jQuery.fancybox.close();
						});
					});
					$("#formCreateUser").submit();
				}
			}
		});
	</script>
</div>