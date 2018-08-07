<div>
	<div id="userCreateApp">
		<h2 class="page-title">Usuário <small> | Cadastro de novo Usuário</small></h2>
		<form action="{{ route('users.store') }}" id="formCreateUser" data-prefix="usr">
			@include('app.users._form')
		</form>
	</div>

	<script type="text/javascript">
		var userCreateApp = new Vue({
			el: '#userCreateApp',
			data: {
				user: {
					street: '',
					district: '',
					city: '',
					state: '',
					role: 'partner',
				},
				parents: [
					@foreach($users as $user)
						{!! '{ id: '.$user->id.', search: "'.$user->search.'"},' !!}
					@endforeach
				]
			},
			mounted: function(){
				var self = this;
				$("label.btn").click(function(event) {
					var child = $(this).children().first();
					self[child.attr('table')][child.attr('field')] = child.val();
				});
				$("#usr_parent_name").cAutocomplete({
					items: self.parents, 
					searchAttr: "search", 
					idAttr: "id",
					idField: "#usr_parent_id"
				});
				$("#formCreateUser").cValidate({
					data: self.user,
					unmask: ['phone1', 'phone2'],
					success: 'Usuário criado com sucesso!',
					error: 'Falha ao criar usuário!',
					redirect: {!! !Auth::user() ? "'".route('login')."'" : 'false' !!},
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