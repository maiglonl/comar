<div>
	<div id="userEditApp">
		<h2 class="page-title">Usuário <small> | Edição de Usuário</small></h2>
		<form action="{{ route('users.update', [$user->id]) }}" id="formEditUser" data-prefix="usr">
			@include('app.users._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#userEditApp',
			data: {
				user: {!! $user->toJson() !!}
			},
			mounted: function(){
				var self = this;
				$("label.btn").click(function(event) {
					var child = $(this).children().first();
					self[child.attr('table')][child.attr('field')] = child.val();
				});
				$("#formEditUser").cValidate({
					data: self.user,
					isPut: true,
					success: 'Usuário atualizado com sucesso!',
					error: 'Falha ao atualizar usuário!',
				});
			},
			methods:{
				submitFormEditUser: function (){ 
					$("#formEditUser").submit();
				}
			},
			watch:{
				'user.zipcode': function(val){
					var self = this;
					if(val.length == 9){
						$.getJSON("https://viacep.com.br/ws/"+ val.replace(/\W/g, '') +"/json/?callback=?", function(dados) {
							self.user.street = dados.logradouro;
							self.user.district = dados.bairro;
							self.user.city = dados.localidade;
							self.user.state = dados.uf;
						});
					}
				}
			}
		});
	</script>
</div>