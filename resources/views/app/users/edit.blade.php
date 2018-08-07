<div>
	<div id="userEditApp">
		<h2 class="page-title">Usuário <small> | Edição de Usuário</small></h2>
		<form action="{{ route('users.update', [$user->id]) }}" id="formEditUser" method="PUT" data-prefix="usr">
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
					unmask: ['phone1', 'phone2'],
					success: 'Usuário atualizado com sucesso!',
					error: 'Falha ao atualizar usuário!',
				});
			},
			watch:{
				'user.zipcode': function(val){
					var self = this;
					if(val.length == 9){
						setAddressData(val, self.user);
					}
				}
			}
		});
	</script>
</div>